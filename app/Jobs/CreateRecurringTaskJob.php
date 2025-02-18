<?php

namespace App\Jobs;

use App\Models\Task;
use App\Models\ContentTask;
use App\Models\OnsiteTask;
use App\Models\OffsiteTask;
use App\Models\StartingKwUrl;
use App\Models\DesignTask;
use App\Models\DevelopmentTask;
use App\Models\AmTask;
use App\Models\SeoAdminTask;
use App\Models\AdminTask;
use App\Models\SalesTask;
use App\Models\TaskAttachment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class CreateRecurringTaskJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function handle()
    {
        if (!$this->task->is_recurring || $this->task->repeat_count <= 0) {
            return;
        }

        $now = Carbon::now('Asia/Dubai');
        $nextStartDate = $now;
        $nextDueDate = Carbon::parse($this->task->due_date)->setTimezone('Asia/Dubai');

        for ($i = 1; $i <= $this->task->repeat_count; $i++) {

            $nextStartDate->addHours($this->task->repeat_interval);
            $nextDueDate->addHours($this->task->repeat_interval);

            $newTask = Task::create([
                'title' => $this->task->title,
                'description' => $this->task->description,
                'project_id' => $this->task->project_id,
                'department_id' => $this->task->department_id,
                'created_by' => $this->task->created_by,
                'assigned_to' => $this->task->assigned_to,
                'priority' => $this->task->priority,
                'type' => $this->task->type,
                'status' => 'Todo',
                'due_date' => $nextDueDate,
                'start_date' => $nextStartDate,
                'is_recurring' => false,
            ]);

            $this->createTaskTypeEntry($this->task);

            if ($this->task->attachments) {
                foreach ($this->task->attachments as $file) {
                    $path = $file->store('task_attachments', 'public');
                    TaskAttachment::create([
                        'task_id' => $newTask->id,
                        'file_path' => $path,
                    ]);
                }
            }
        }
    }

    private function createTaskTypeEntry($task)
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
                    'task_id' => $task->id,
                    'sub_type' => $relatedData->sub_type,
                    'number_of_pages' => $relatedData->number_of_pages,
                    'no_of_words' => $relatedData->no_of_words,
                    'closing_input' => $relatedData->closing_input,
                ]);
                break;

            case 'ONSITE':
                OnsiteTask::create([
                    'task_id' => $task->id,
                    'sub_type' => $relatedData->sub_type,
                    'closing_input' => $relatedData->closing_input,
                ]);
                break;

            case 'OFFSITE':
                $offsiteTask = OffsiteTask::create([
                    'task_id' => $task->id,
                    'sub_type' => $relatedData->sub_type,
                ]);
                $kwUrls = $offsiteTask->startingKwUrls()->get();
                if (!empty($kwUrls) && is_array($kwUrls)) {
                    foreach ($kwUrls as $kw) {
                        if (isset($kw['keyword']) && isset($kw['url'])) {
                            $domain = parse_url($kw['url'], PHP_URL_HOST);

                            StartingKwUrl::create([
                                'keyword' => $kw['keyword'],
                                'url' => $kw['url'],
                                'domain' => $domain,
                                'task_id' => $task->id,
                                'project_id' => $task->project_id,
                                'offsite_task_id' => $offsiteTask->id,
                            ]);
                        }
                    }
                }

                break;

            case 'DESIGN':
                DesignTask::create([
                    'task_id' => $task->id,
                    'sub_type' => $relatedData->sub_type,
                    'input_number' => $relatedData->input_number,
                    'closing_input' => $relatedData->closing_input,
                ]);
                break;

            case 'DEVELOPMENT':
                DevelopmentTask::create([
                    'task_id' => $task->id,
                    'sub_type' => $relatedData->sub_type,
                    'closing_input' => $relatedData->closing_input,
                ]);
                break;

            case 'AM':
                AmTask::create([
                    'task_id' => $task->id,
                    'sub_type' => $relatedData->sub_type,
                ]);
                break;

            case 'SEO ADMIN':
                SeoAdminTask::create([
                    'task_id' => $task->id,
                    'sub_type' => 'ADHOC',
                ]);
                break;

            case 'ADMIN':
                AdminTask::create([
                    'task_id' => $task->id,
                    'sub_type' => 'ADHOC',
                ]);
                break;

            case 'SALES':
                SalesTask::create([
                    'task_id' => $task->id,
                    'sub_type' => 'ADHOC',
                ]);
                break;
        }
    }
}
