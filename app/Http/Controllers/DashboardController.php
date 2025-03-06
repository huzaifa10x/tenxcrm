<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskCompletion;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

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

            $pendingTasks = $projects->flatMap(function ($project) {
                return $project->tasks->where('status', 'Todo')->values();
            });

            $completedTasks = $projects->flatMap(function ($project) {
                return $project->tasks->where('status', 'Completed')->values();
            });

            $assignedTasks = Task::where('created_by', $user->id)->get();

            $accountProjects = Project::whereHas('accountManagers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();

            $last15Days = collect(range(0, 14))->map(function ($day) {
                return Carbon::now()->subDays($day)->format('Y-m-d');
            })->reverse()->values();

            $tasksLast15Days = Task::where('assigned_to', $user->id)
                ->whereBetween('created_at', [Carbon::now()->subDays(14)->startOfDay(), Carbon::now()->endOfDay()])
                ->selectRaw("DATE(created_at) as date, COUNT(*) as count")
                ->groupBy('date')
                ->pluck('count', 'date');

            $taskData = $last15Days->mapWithKeys(function ($date) use ($tasksLast15Days) {
                return [$date => $tasksLast15Days[$date] ?? 0];
            });

            return view('dashboard', compact(
                'role',
                'currentPortfolio',
                'projects',
                'campaignCounts',
                'pendingTasks',
                'completedTasks',
                'assignedTasks',
                'accountProjects',
                'taskData',
            ));
        } elseif ($role == 'CONTENT') {
            $projects = Project::whereHas('contentWriters', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();
            $currentPortfolio = $projects->sum(function ($project) {
                return ($project->contract_value && $project->contract_term) ?
                    ($project->contract_value / $project->contract_term) : 0;
            });
            $campaignCounts = Project::whereHas('contentWriters', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->selectRaw("status, COUNT(*) as count")
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status');

            $pendingTasks = Task::where('status', 'Todo')->where('assigned_to', $user->id)->get();
            $completedTasks = Task::where('status', 'Completed')->where('assigned_to', $user->id)->get();

            $pendingTasksCount = Task::where('status', 'Todo')->where('assigned_to', $user->id)->count();
            $completedTasksCount = Task::where('status', 'Completed')->where('assigned_to', $user->id)->count();
            $totalTasksCount = Task::where('assigned_to', $user->id)->count();

            $assignedTasks = Task::where('created_by', $user->id)->get();

            $accountProjects = Project::whereHas('contentWriters', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();

            $completedWords = TaskCompletion::whereHas('task', function ($query) use ($projects) {
                $query->whereIn('project_id', $projects->pluck('id'))
                    ->where('status', 'Completed');
            })
                ->where('field_name', 'number_of_words')
                ->sum('field_value');
            $pendingWords = TaskCompletion::whereHas('task', function ($query) use ($projects) {
                $query->whereIn('project_id', $projects->pluck('id'))
                    ->where('status', 'Todo');
            })
                ->where('field_name', 'number_of_words')
                ->sum('field_value');
            $pendingWords = $pendingWords ?? 0;

            $last15Days = collect(range(0, 14))->map(function ($day) {
                return Carbon::now()->subDays($day)->format('Y-m-d');
            })->reverse()->values();

            $tasksLast15Days = Task::where('assigned_to', $user->id)
                ->whereBetween('created_at', [Carbon::now()->subDays(14)->startOfDay(), Carbon::now()->endOfDay()])
                ->selectRaw("DATE(created_at) as date, COUNT(*) as count")
                ->groupBy('date')
                ->pluck('count', 'date');

            $taskData = $last15Days->mapWithKeys(function ($date) use ($tasksLast15Days) {
                return [$date => $tasksLast15Days[$date] ?? 0];
            });

            return view('dashboard', compact(
                'role',
                'currentPortfolio',
                'projects',
                'campaignCounts',
                'pendingTasks',
                'completedTasks',
                'assignedTasks',
                'accountProjects',
                'pendingTasksCount',
                'completedTasksCount',
                'totalTasksCount',
                'completedWords',
                'pendingWords',
                'taskData',
            ));
        }

        return view('dashboard', compact('role'));
    }
}
