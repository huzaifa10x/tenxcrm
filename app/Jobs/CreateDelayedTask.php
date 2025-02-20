<?php

namespace App\Jobs;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateDelayedTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $projectId, $title, $assignedTo;

    public function __construct($projectId, $title, $assignedTo)
    {
        $this->projectId = $projectId;
        $this->title = $title;
        $this->assignedTo = $assignedTo;
    }

    public function handle()
    {
        Task::create([
            'project_id' => $this->projectId,
            'title' => $this->title,
            'assigned_to' => $this->assignedTo,
            'status' => 'Todo',
        ]);
    }
}
