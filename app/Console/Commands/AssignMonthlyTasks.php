<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\Project;
use Carbon\Carbon;

class AssignMonthlyTasks extends Command
{
    protected $signature = 'assign:monthly-tasks';
    protected $description = 'Assigns specific tasks to the first AM of active projects on the 1st of each month';
    public function handle()
    {
        $activeProjects = Project::whereNotIn('status', ['Completed', 'Cancelled', 'Expired'])->get();
        if ($activeProjects->isEmpty()) {
            $this->info('No active projects found.');
            return;
        }
        $tasks = [
            'Performance Report' => 'Assign on 1st of each month to AM',
            'Campaign Meeting (Deep-dive) with Tech' => 'Assign on 1st of each month to AM',
            'Campaign Meeting (Deep-dive) with Client' => 'Assign on 1st of each month to AM'
        ];
        foreach ($activeProjects as $project) {
            $accountManager = $project->accountManagers->pluck('id')->first();
            foreach ($tasks as $title => $description) {
                Task::create([
                    'title' => $title,
                    'description' => $description,
                    'assigned_to' => $accountManager,
                    'status' => 'Todo',
                    'start_date' => Carbon::now()->startOfMonth()->toDateTimeString(), // Proper format
                    'due_date' => Carbon::now()->startOfMonth()->addDays(3),
                    'created_by' => 1,
                    'is_automated' => 1,
                    'project_id' => $project->id,
                    'type' => 'ADHOC'
                ]);
            }
        }
        $this->info('Monthly tasks assigned to Account Managers of active projects successfully.');
    }
}