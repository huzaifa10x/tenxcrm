<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Project;
use App\Models\Task;

class ContractRenewalTasks extends Command
{
    protected $signature = 'tasks:contract-renewal';
    protected $description = 'Assign renewal tasks to AM based on contract expiration';
    public function handle()
    {
        $projects = Project::whereNotIn('status', ['Completed', 'Cancelled', 'Expired'])->get();
        $today = Carbon::now()->startOfDay();
        foreach ($projects as $project) {
            $accountManager = $project->accountManagers->pluck('id')->first();
            $contractStart = Carbon::parse($project->contract_start_date);
            $contractEnd = $contractStart->addMonths($project->contract_term)->startOfDay();
            if ($contractEnd->equalTo($today)) {
                $project->update(['status' => 'Expired']);
                $this->info("Project ID: {$project->id} marked as Expired.");
            }
            $reminderDate = $contractEnd->copy()->subMonth();
            $contractDate = $contractEnd->copy();
            $statusDate = $contractEnd->copy();
            $this->assignTask($project, $accountManager, "Renewal Reminder", $reminderDate);
            // $this->assignTask($project, $accountManager, "Renewal Contract", $contractDate);
            // $this->assignTask($project, $accountManager, "Renewal Status", $statusDate);
        }
        $this->info('Contract renewal tasks assigned successfully.');
    }

    private function assignTask($project, $assignedTo, $title, $date)
    {
        if (Carbon::now()->greaterThanOrEqualTo($date)) {
            Task::create([
                'title' => $title,
                'description' => "Renewal Reminder Task",
                'assigned_to' => $assignedTo,
                'status' => 'Todo',
                'start_date' => $date,
                'due_date' => Carbon::now()->startOfMonth()->addDays(3),
                'created_by' => 1,
                'is_automated' => 1,
                'project_id' => $project->id,
                'type' => 'ADHOC'
            ]);
        }
    }
}
