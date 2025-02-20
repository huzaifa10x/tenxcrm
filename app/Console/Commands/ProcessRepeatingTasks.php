<?php

namespace App\Console\Commands;

use App\Models\AdminTask;
use App\Models\AmTask;
use App\Models\ContentTask;
use App\Models\DesignTask;
use App\Models\DevelopmentTask;
use App\Models\OffsiteTask;
use App\Models\OnsiteTask;
use App\Models\Project;
use Illuminate\Console\Command;
use App\Models\RepeatingTask;
use App\Models\SalesTask;
use App\Models\SeoAdminTask;
use App\Models\StartingKwUrl;
use App\Models\Task;
use App\Models\TaskAttachment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProcessRepeatingTasks extends Command
{
    protected $signature = 'tasks:process-repeating';
    protected $description = 'Process tasks that need to repeat based on their interval.';

    public function handle()
    {
        // Get all repeating tasks that need to be processed
        $repeatingTasks = RepeatingTask::where('next_run_at', '<=', now())->get();
        $now = Carbon::now('Asia/Dubai');  // Set current time in Asia/Dubai timezone
        $dubaiTime = Carbon::now()->timezone('Asia/Dubai');

        foreach ($repeatingTasks as $repeatingTask) {
            // Set the next due date based on the repeating task's due date
            $nextDueDate = Carbon::parse($repeatingTask->task->due_date)->setTimezone('Asia/Dubai');

            // Calculate the next start date (start date is a similar concept, based on repeat interval)
            $nextStartDate = $now->copy()->addHours($repeatingTask->repeat_every);  // Increment by the interval for the next start date

            // Create a new task based on the parent task (repeating task's associated task)
            $newTask = Task::create([
                'title' => $repeatingTask->task->title,
                'description' => $repeatingTask->task->description,
                'project_id' => $repeatingTask->task->project_id,
                'department_id' => $repeatingTask->task->department_id,
                'created_by' => $repeatingTask->task->created_by,
                'assigned_to' => $repeatingTask->task->assigned_to,
                'priority' => $repeatingTask->task->priority,
                'type' => $repeatingTask->task->type,
                'status' => 'Todo',  // New task starts as 'Todo'
                'due_date' => $nextDueDate,
                'start_date' => $nextStartDate,
                'repeat_task' => 0,
                'parent_task_id' => $repeatingTask->task->id,
            ]);

            if ($repeatingTask->task->attachments()->exists()) {
                foreach ($repeatingTask->task->attachments as $file) {
                    TaskAttachment::create([
                        'task_id' => $newTask->id,
                        'file_path' => $file->file_path,
                    ]);
                }
            }

            $this->createTaskTypeEntry($repeatingTask->task, $newTask->id);

            $repeatingTask->next_run_at = now()->addHours($repeatingTask->repeat_every);  // Set the next time for this task to repeat
            $repeatingTask->save();
        }

        if ($now->day == 1) {
            $projects = Project::where("project_type", "SEO")->get();

            foreach ($projects as $project) {
                $accountManager = $project->accountManagers[0]->id;

                $expiryDate = Carbon::parse($project->contract_start_date)->addMonths($project->contract_term);

                $reminderDates = [
                    '1 Month Before Expiry' => $expiryDate->copy()->subMonth(),
                    '15 Days Before Expiry' => $expiryDate->copy()->subDays(15),
                    'Day of Expiry' => $expiryDate
                ];

                foreach ($reminderDates as $title => $date) {
                    if ($date->isToday()) {
                        Task::create([
                            'project_id' => $project->id,
                            'title' => "Renewal Reminder - $title",
                            'assigned_to' => $accountManager,
                            'status' => 'Todo',
                            'due_date' => $date,
                            'created_by' => 1,
                            'start_date' => $dubaiTime,
                        ]);
                    }
                }

                Task::insert([
                    [
                        'project_id' => $project->id,
                        'title' => 'Performance Report',
                        'assigned_to' => $accountManager,
                        'status' => 'Todo',
                        'created_by' => 1,
                        'start_date' => $dubaiTime,
                    ],
                    [
                        'project_id' => $project->id,
                        'title' => 'Campaign Meeting (Deep-dive) with Tech',
                        'assigned_to' => $accountManager,
                        'status' => 'Todo',
                        'created_by' => 1,
                        'start_date' => $dubaiTime,
                    ],
                ]);
            }
        }

        $this->info('Repeating tasks processed successfully.');
    }

    private function createTaskTypeEntry($task, $newTaskID)
    {
        $relatedData = null;

        switch ($task->type) {
            case 'CONTENT':
                $relatedData = ContentTask::where('task_id', $task->id)->first();
                break;
            case 'ONSITE':
                $relatedData = OnsiteTask::where('task_id', $task->id)->first();
                break;
            case 'OFFSITE':
                $relatedData = OffsiteTask::with('startingKwUrls')->where('task_id', $task->id)->first();
                break;
            case 'DESIGN':
                $relatedData = DesignTask::where('task_id', $task->id)->first();
                break;
            case 'DEVELOPMENT':
                $relatedData = DevelopmentTask::where('task_id', $task->id)->first();
                break;
            case 'AM':
                $relatedData = AmTask::where('task_id', $task->id)->first();
                break;
            case 'ADMIN':
                $relatedData = AdminTask::where('task_id', $task->id)->first();
                break;
            case 'SALES':
                $relatedData = SalesTask::where('task_id', $task->id)->first();
                break;
            case 'ADHOC':
                $relatedData = null;
                break;
            default:
                $relatedData = null;
                break;
        }
        switch ($task->type) {
            case 'CONTENT':
                ContentTask::create([
                    'task_id' => $newTaskID,
                    'sub_type' => $relatedData->sub_type,
                    'number_of_pages' => $relatedData->number_of_pages,
                    'no_of_words' => $relatedData->no_of_words,
                    'closing_input' => $relatedData->closing_input,
                ]);
                break;

            case 'ONSITE':
                OnsiteTask::create([
                    'task_id' => $newTaskID,
                    'sub_type' => $relatedData->sub_type,
                    'closing_input' => $relatedData->closing_input,
                ]);
                break;

            case 'OFFSITE':

                $offsiteTask = OffsiteTask::create([
                    'task_id' => $newTaskID,
                    'sub_type' => $relatedData->sub_type,
                ]);

                $kwUrls = $relatedData->startingKwUrls()->get();

                if ($kwUrls instanceof \Illuminate\Support\Collection && $kwUrls->isNotEmpty()) {
                    foreach ($kwUrls as $kw) {
                        if (!empty($kw->keyword) && !empty($kw->url)) {
                            $domain = parse_url($kw->url, PHP_URL_HOST);

                            $startingKwUrl = StartingKwUrl::create([
                                'keyword' => $kw->keyword,
                                'url' => $kw->url,
                                'domain' => $domain,
                                'task_id' => $newTaskID,
                                'project_id' => $task->project_id ?? null,
                                'offsite_task_id' => $offsiteTask->id,
                            ]);
                        }
                    }
                }

                break;

            case 'DESIGN':
                DesignTask::create([
                    'task_id' => $newTaskID,
                    'sub_type' => $relatedData->sub_type,
                    'input_number' => $relatedData->input_number,
                    'closing_input' => $relatedData->closing_input,
                ]);
                break;

            case 'DEVELOPMENT':
                DevelopmentTask::create([
                    'task_id' => $newTaskID,
                    'sub_type' => $relatedData->sub_type,
                    'closing_input' => $relatedData->closing_input,
                ]);
                break;

            case 'AM':
                AmTask::create([
                    'task_id' => $newTaskID,
                    'sub_type' => $relatedData->sub_type,
                ]);
                break;

            case 'SEO ADMIN':
                SeoAdminTask::create([
                    'task_id' => $newTaskID,
                    'sub_type' => 'ADHOC',
                ]);
                break;

            case 'ADMIN':
                AdminTask::create([
                    'task_id' => $newTaskID,
                    'sub_type' => 'ADHOC',
                ]);
                break;

            case 'SALES':
                SalesTask::create([
                    'task_id' => $newTaskID,
                    'sub_type' => 'ADHOC',
                ]);
                break;
        }
    }
}
