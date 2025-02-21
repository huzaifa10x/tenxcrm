<?php

namespace App\Console\Commands;

use App\Models\AdminTask;
use App\Models\AmTask;
use App\Models\ContentTask;
use App\Models\DesignTask;
use App\Models\DevelopmentTask;
use App\Models\OffsiteTask;
use App\Models\OnsiteTask;
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
        $repeatingTasks = RepeatingTask::where('next_run_at', '<=', now())->get();
        $now = Carbon::now('Asia/Dubai');

        foreach ($repeatingTasks as $repeatingTask) {
            if (!is_null($repeatingTask->repeat_count) && $repeatingTask->completed_repeats >= $repeatingTask->repeat_count) {
                $repeatingTask->delete();
                continue;
            }

            $nextDueDate = Carbon::parse($repeatingTask->task->due_date)->setTimezone('Asia/Dubai');
            $nextStartDate = $now->copy()->addHours($repeatingTask->repeat_every); // Increment by the interval

            $newTask = Task::create([
                'is_automated' => 1,
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

            $repeatingTask->next_run_at = now()->addHours($repeatingTask->repeat_every);

            if (!is_null($repeatingTask->repeat_count)) {
                $repeatingTask->completed_repeats += 1;
            }

            $repeatingTask->save();
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
