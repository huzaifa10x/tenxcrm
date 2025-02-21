<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class CreateTaskJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $projectId;
    protected $title;
    protected $days;
    protected $seoAdmin;
    protected $dubaiTime;

    public function __construct($projectId, $title, $days, $seoAdmin, $dubaiTime)
    {
        $this->projectId = $projectId;
        $this->title = $title;
        $this->days = $days;
        $this->seoAdmin = $seoAdmin;
        $this->dubaiTime = $dubaiTime;
    }

    public function handle()
    {
        Task::create([
            'created_by' => 1,
            'project_id' => $this->projectId,
            'title' => $this->title,
            'assigned_to' => $this->seoAdmin,
            'status' => 'Todo',
            'start_date' => $this->dubaiTime,
            'type' => 'ADHOC',
            'description' => 'system generated task'
        ]);
    }
}
