<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\ContentTask;
use App\Models\TaskCompletion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContentWriterController extends Controller
{
    public function index()
    {
        // Get all content writers
        $contentWriters = User::whereHas('roles', function ($query) {
            $query->where('name', 'CONTENT');
        })->withCount([
            'assignedTasks as total_tasks',
            'assignedTasks as completed_tasks' => function ($query) {
                $query->where('status', 'Completed');
            },
            'assignedTasks as pending_tasks' => function ($query) {
                $query->whereIn('status', ['Todo', 'In Progress', 'Review']);
            }
        ])->get();

        return view('admin.content-writers.index', compact('contentWriters'));
    }

    public function show(Request $request, $userId)
    {
        $writer = User::with('roles')->findOrFail($userId);

        // Validate user is a content writer
        if (!$writer->roles->contains('name', 'CONTENT')) {
            abort(403, 'User is not a content writer');
        }

        // Get filter parameters
        $startDate = $request->input('start_date', Carbon::now()->subMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());
        $projectId = $request->input('project_id');
        $status = $request->input('status');

        // Base query for tasks
        $tasksQuery = Task::where('assigned_to', $userId)
            ->where('type', 'CONTENT')
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($projectId) {
            $tasksQuery->where('project_id', $projectId);
        }

        if ($status) {
            $tasksQuery->where('status', $status);
        }

        // Get tasks with relationships
        $tasks = $tasksQuery->with([
            'project',
            'contentTask',
            'completions' => function ($query) {
                $query->where('field_name', 'number_of_words');
            }
        ])->orderBy('created_at', 'desc')->get();

        // Calculate statistics
        $stats = [
            'total_tasks' => $tasks->count(),
            'completed_tasks' => $tasks->where('status', 'Completed')->count(),
            'pending_tasks' => $tasks->whereIn('status', ['Todo', 'In Progress', 'Review'])->count(),
            'total_words_completed' => $tasks->where('status', 'Completed')->sum(function ($task) {
                return $task->completions->sum('field_value');
            }),
            'total_words_pending' => $tasks->whereIn('status', ['Todo', 'In Progress', 'Review'])->sum(function ($task) {
                return $task->contentTask->no_of_words ?? 0;
            }),
        ];

        // Quick date filters
        $quickFilters = [
            'today' => [
                'start' => Carbon::today()->toDateString(),
                'end' => Carbon::today()->toDateString(),
                'label' => 'Today'
            ],
            'yesterday' => [
                'start' => Carbon::yesterday()->toDateString(),
                'end' => Carbon::yesterday()->toDateString(),
                'label' => 'Yesterday'
            ],
            'last_7_days' => [
                'start' => Carbon::now()->subDays(7)->toDateString(),
                'end' => Carbon::now()->toDateString(),
                'label' => 'Last 7 Days'
            ],
            'last_30_days' => [
                'start' => Carbon::now()->subDays(30)->toDateString(),
                'end' => Carbon::now()->toDateString(),
                'label' => 'Last 30 Days'
            ],
            'this_month' => [
                'start' => Carbon::now()->startOfMonth()->toDateString(),
                'end' => Carbon::now()->toDateString(),
                'label' => 'This Month'
            ],
            'last_month' => [
                'start' => Carbon::now()->subMonth()->startOfMonth()->toDateString(),
                'end' => Carbon::now()->subMonth()->endOfMonth()->toDateString(),
                'label' => 'Last Month'
            ]
        ];

        // Get projects for filter
        $projects = Task::where('assigned_to', $userId)
            ->where('type', 'CONTENT')
            ->with('project')
            ->get()
            ->pluck('project')
            ->unique('id')
            ->filter();

        // Task completion timeline (last 30 days)
        $timelineData = Task::where('assigned_to', $userId)
            ->where('type', 'CONTENT')
            ->where('status', 'Completed')
            ->whereBetween('completed_date', [Carbon::now()->subDays(29), Carbon::now()])
            ->select(DB::raw('DATE(completed_date) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->get()
            ->pluck('count', 'date');

        $timelineDates = collect(range(29, 0))->map(function ($i) {
            return Carbon::now()->subDays($i)->format('Y-m-d');
        });

        $timeline = $timelineDates->map(function ($date) use ($timelineData) {
            return [
                'date' => Carbon::parse($date)->format('M d'),
                'count' => $timelineData[$date] ?? 0
            ];
        });

        // Word count by project
        $wordsByProject = $tasks->where('status', 'Completed')
            ->groupBy('project_id')
            ->map(function ($projectTasks) {
                return [
                    'project' => $projectTasks->first()->project->title ?? 'Unknown',
                    'words' => $projectTasks->sum(function ($task) {
                        return $task->completions->sum('field_value');
                    }),
                    'tasks' => $projectTasks->count()
                ];
            })->sortByDesc('words')->values();

        return view('admin.content-writers.show', compact(
            'writer',
            'tasks',
            'stats',
            'quickFilters',
            'projects',
            'timeline',
            'wordsByProject',
            'startDate',
            'endDate',
            'projectId',
            'status'
        ));
    }
}
