<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskCompletion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $role = $user->roles->first()->name;

        $latestNotifications = $user->notifications()->latest()->take(5)->get();

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

        $filter = $request->query('filter', 'today');
        if ($filter === 'monthly') {
            $startDate = Carbon::now()->subMonth()->startOfMonth();
        } elseif ($filter === 'weekly') {
            $startDate = Carbon::now()->subWeek()->startOfWeek();
        } elseif ($filter === 'today') {
            $startDate = Carbon::now()->startOfDay();
        } else {
            $startDate = Carbon::now()->subYear()->startOfYear();
        }
        $endDate = Carbon::now()->endOfDay();
        $pendingTasksCountGlobal = Task::whereIn('status', ['Todo', 'In Progress', 'Review'])
            ->where('assigned_to', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $completedTasksCountGlobal = Task::where('status', 'Completed')
            ->where('assigned_to', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $createdTasksCountGlobal = Task::where('created_by', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $collaboratorTasksCountGlobal = Task::whereExists(function ($subQuery) use ($user) {
            $subQuery->select(DB::raw(1))
                ->from('task_collaborators')
                ->whereRaw('task_collaborators.task_id = tasks.id')
                ->where('task_collaborators.user_id', $user->id);
        })->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $totalTasks = $pendingTasksCountGlobal + $completedTasksCountGlobal + $createdTasksCountGlobal + $collaboratorTasksCountGlobal;
        $totalTasks = max($totalTasks, 1);
        $taskPercentages = [
            'pending' => round(($pendingTasksCountGlobal / $totalTasks) * 100),
            'completed' => round(($completedTasksCountGlobal / $totalTasks) * 100),
            'created' => round(($createdTasksCountGlobal / $totalTasks) * 100),
            'collaborator' => round(($collaboratorTasksCountGlobal / $totalTasks) * 100),
        ];
        if ($request->ajax()) {
            return response()->json([
                'pendingTasksCountGlobal' => $pendingTasksCountGlobal,
                'completedTasksCountGlobal' => $completedTasksCountGlobal,
                'createdTasksCountGlobal' => $createdTasksCountGlobal,
                'collaboratorTasksCountGlobal' => $collaboratorTasksCountGlobal,
                'taskPercentages' => $taskPercentages,
            ]);
        }

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
                'pendingTasksCountGlobal',
                'completedTasksCountGlobal',
                'createdTasksCountGlobal',
                'collaboratorTasksCountGlobal',
                'taskPercentages',
                'latestNotifications',
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
                'pendingTasksCountGlobal',
                'completedTasksCountGlobal',
                'createdTasksCountGlobal',
                'collaboratorTasksCountGlobal',
                'taskPercentages',
                'latestNotifications',
            ));
        }

        return view('dashboard', compact(
            'role',
            'taskData',
            'pendingTasksCountGlobal',
            'completedTasksCountGlobal',
            'createdTasksCountGlobal',
            'collaboratorTasksCountGlobal',
            'taskPercentages',
            'latestNotifications',
        ));
    }
    // public function index(Request $request)
    // {
    //     $user = auth()->user();
    //     $role = $user->roles->first()->name;
    //     $latestNotifications = $user->notifications()->latest()->take(5)->get();

    //     $last15Days = collect(range(0, 14))->map(fn($day) => Carbon::now()->subDays($day)->format('Y-m-d'))->reverse();
    //     $tasksLast15Days = Task::where('assigned_to', $user->id)
    //         ->whereBetween('created_at', [Carbon::now()->subDays(14)->startOfDay(), Carbon::now()->endOfDay()])
    //         ->selectRaw("DATE(created_at) as date, COUNT(*) as count")
    //         ->groupBy('date')
    //         ->pluck('count', 'date');

    //     $taskData = $last15Days->mapWithKeys(fn($date) => [$date => $tasksLast15Days[$date] ?? 0]);

    //     // Task filtering
    //     $filter = $request->query('filter', 'today');
    //     $startDate = match ($filter) {
    //         'monthly' => Carbon::now()->subMonth()->startOfMonth(),
    //         'weekly' => Carbon::now()->subWeek()->startOfWeek(),
    //         'today' => Carbon::now()->startOfDay(),
    //         default => Carbon::now()->subYear()->startOfYear(),
    //     };
    //     $endDate = Carbon::now()->endOfDay();

    //     // Global task counts
    //     $pendingTasksCountGlobal = Task::whereIn('status', ['Todo', 'In Progress', 'Review'])
    //         ->where('assigned_to', $user->id)
    //         ->whereBetween('created_at', [$startDate, $endDate])
    //         ->count();

    //     $completedTasksCountGlobal = Task::where('status', 'Completed')
    //         ->where('assigned_to', $user->id)
    //         ->whereBetween('created_at', [$startDate, $endDate])
    //         ->count();

    //     $createdTasksCountGlobal = Task::where('created_by', $user->id)
    //         ->whereBetween('created_at', [$startDate, $endDate])
    //         ->count();

    //     $collaboratorTasksCountGlobal = Task::whereExists(fn($query) => $query->select(DB::raw(1))
    //         ->from('task_collaborators')
    //         ->whereRaw('task_collaborators.task_id = tasks.id')
    //         ->where('task_collaborators.user_id', $user->id))
    //         ->whereBetween('created_at', [$startDate, $endDate])
    //         ->count();

    //     $totalTasks = max($pendingTasksCountGlobal + $completedTasksCountGlobal + $createdTasksCountGlobal + $collaboratorTasksCountGlobal, 1);
    //     $taskPercentages = [
    //         'pending' => round(($pendingTasksCountGlobal / $totalTasks) * 100),
    //         'completed' => round(($completedTasksCountGlobal / $totalTasks) * 100),
    //         'created' => round(($createdTasksCountGlobal / $totalTasks) * 100),
    //         'collaborator' => round(($collaboratorTasksCountGlobal / $totalTasks) * 100),
    //     ];

    //     // If it's an AJAX request, return JSON
    //     if ($request->ajax()) {
    //         return response()->json(compact(
    //             'pendingTasksCountGlobal',
    //             'completedTasksCountGlobal',
    //             'createdTasksCountGlobal',
    //             'collaboratorTasksCountGlobal',
    //             'taskPercentages'
    //         ));
    //     }

    //     $projects = collect();
    //     $campaignCounts = [];
    //     $pendingTasks = collect();
    //     $completedTasks = collect();
    //     $assignedTasks = collect();
    //     $accountProjects = collect();
    //     $pendingTasksCount = $completedTasksCount = $totalTasksCount = 0;
    //     $completedWords = $pendingWords = 0;
    //     $currentPortfolio = 0;

    //     // Roles-based logic
    //     $roleMappings = [
    //         'AM' => 'accountManagers',
    //         'CONTENT' => 'contentWriters',
    //     ];

    //     if (isset($roleMappings[$role])) {
    //         $relation = $roleMappings[$role];

    //         $projects = Project::whereHas($relation, fn($query) => $query->where('user_id', $user->id))->get();
    //         $currentPortfolio = $projects->sum(
    //             fn($project) => ($project->contract_value && $project->contract_term) ? ($project->contract_value / $project->contract_term) : 0
    //         );

    //         $campaignCounts = Project::whereHas($relation, fn($query) => $query->where('user_id', $user->id))
    //             ->selectRaw("status, COUNT(*) as count")
    //             ->groupBy('status')
    //             ->pluck('count', 'status');

    //         $pendingTasks = Task::where('status', 'Todo')->where('assigned_to', $user->id)->get();
    //         $completedTasks = Task::where('status', 'Completed')->where('assigned_to', $user->id)->get();
    //         $pendingTasksCount = $pendingTasks->count();
    //         $completedTasksCount = $completedTasks->count();
    //         $totalTasksCount = Task::where('assigned_to', $user->id)->count();
    //         $assignedTasks = Task::where('created_by', $user->id)->get();
    //         $accountProjects = $projects;

    //         $completedWords = TaskCompletion::whereHas('task', fn($query) => $query
    //             ->whereIn('project_id', $projects->pluck('id'))
    //             ->where('status', 'Completed'))
    //             ->where('field_name', 'number_of_words')
    //             ->sum('field_value');

    //         $pendingWords = TaskCompletion::whereHas('task', fn($query) => $query
    //             ->whereIn('project_id', $projects->pluck('id'))
    //             ->where('status', 'Todo'))
    //             ->where('field_name', 'number_of_words')
    //             ->sum('field_value') ?? 0;
    //     }

    //     return view('dashboard', compact(
    //         'role',
    //         'currentPortfolio',
    //         'projects',
    //         'campaignCounts',
    //         'pendingTasks',
    //         'completedTasks',
    //         'assignedTasks',
    //         'accountProjects',
    //         'pendingTasksCount',
    //         'completedTasksCount',
    //         'totalTasksCount',
    //         'completedWords',
    //         'pendingWords',
    //         'taskData',
    //         'pendingTasksCountGlobal',
    //         'completedTasksCountGlobal',
    //         'createdTasksCountGlobal',
    //         'collaboratorTasksCountGlobal',
    //         'taskPercentages',
    //         'latestNotifications'
    //     ));
    // }
}
