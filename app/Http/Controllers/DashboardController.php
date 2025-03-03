<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // $activeProjects = Project::whereNotIn('status', ['Completed', 'Cancelled', 'Expired'])->get();
        // if ($activeProjects->isEmpty()) {
        //     $this->info('No active projects found.');
        //     return;
        // }
        // $tasks = [
        //     'Performance Report' => 'Assign on 1st of each month to AM',
        //     'Campaign Meeting (Deep-dive) with Tech' => 'Assign on 1st of each month to AM',
        //     'Campaign Meeting (Deep-dive) with Client' => 'Assign on 1st of each month to AM'
        // ];
        // foreach ($activeProjects as $project) {
        //     $accountManager = $project->accountManagers->pluck('id')->first();
        //     foreach ($tasks as $title => $description) {
        //         Task::create([
        //             'title' => $title,
        //             'description' => $description,
        //             'assigned_to' => $accountManager,
        //             'status' => 'Todo',
        //             'start_date' => Carbon::now()->startOfMonth()->toDateTimeString(), // Proper format
        //             'due_date' => Carbon::now()->startOfMonth()->addDays(3),
        //             'created_by' => 1,
        //             'is_automated' => 1,
        //             'project_id' => $project->id,
        //             'type' => 'ADHOC'
        //         ]);
        //     }
        // }

        // $projects = Project::whereNotIn('status', ['Completed', 'Cancelled', 'Expired'])->get();
        // $today = Carbon::now()->startOfDay();
        // foreach ($projects as $project) {
        //     $accountManager = $project->accountManagers->pluck('id')->first();
        //     $contractStart = Carbon::parse($project->contract_start_date);
        //     $contractEnd = $contractStart->addMonths($project->contract_term)->startOfDay();
        //     if ($contractEnd->equalTo($today)) {
        //         $project->update(['status' => 'Expired']);
        //     }
        //     $reminderDate = $contractEnd->copy()->subMonth();
        //     $contractDate = $contractEnd->copy();
        //     $statusDate = $contractEnd->copy();
        //     Task::create([
        //         'title' => "Renewal Reminder",
        //         'description' => "Renewal Reminder Task",
        //         'assigned_to' => $accountManager,
        //         'status' => 'Todo',
        //         'start_date' => $today,
        //         'due_date' => Carbon::now()->startOfMonth()->addDays(3),
        //         'created_by' => 1,
        //         'is_automated' => 1,
        //         'project_id' => $project->id,
        //         'type' => 'ADHOC'
        //     ]);
        //     Task::create([
        //         'title' => "Renewal Contract",
        //         'description' => "Renewal Reminder Task",
        //         'assigned_to' => $accountManager,
        //         'status' => 'Todo',
        //         'start_date' => $today,
        //         'due_date' => Carbon::now()->startOfMonth()->addDays(3),
        //         'created_by' => 1,
        //         'is_automated' => 1,
        //         'project_id' => $project->id,
        //         'type' => 'ADHOC'
        //     ]);
        //     Task::create([
        //         'title' => "Renewal Status",
        //         'description' => "Renewal Reminder Task",
        //         'assigned_to' => $accountManager,
        //         'status' => 'Todo',
        //         'start_date' => $today,
        //         'due_date' => Carbon::now()->startOfMonth()->addDays(3),
        //         'created_by' => 1,
        //         'is_automated' => 1,
        //         'project_id' => $project->id,
        //         'type' => 'ADHOC'
        //     ]);
        // }

        $role = $user->roles->first()->name;

        if ($role == 'AM') {
            $projects = Project::whereHas('accountManagers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();
            $currentPortfolio = $projects->sum(function ($project) {
                return ($project->contract_value && $project->contract_term) ?
                    ($project->contract_value / $project->contract_term) : 0;
            });
            $campaignCounts = Project::whereHas('accountManagers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->selectRaw("status, COUNT(*) as count")
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status');
            return view('dashboard', compact('role', 'currentPortfolio', 'projects', 'campaignCounts'));
        }

        return view('dashboard', compact('role'));
    }
}
