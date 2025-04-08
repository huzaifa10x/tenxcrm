<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Automation;
use App\Models\GeneralSetting;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskCompletion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $extraData = [];
        $role = $user->roles->first()->name;
        $latestNotifications = $user->notifications()->latest()->take(5)->get();

        $settings = GeneralSetting::first();

        if ($settings) {
            $closingTime = Carbon::parse($settings->office_close_time)->toTimeString();
            $currentTime = now()->toTimeString();
            $pendingAttendances = Attendance::where('date', '<', today())
                ->whereNull('punch_out')
                ->get();
            if ($currentTime > $closingTime) {
                $todayAttendances = Attendance::where('date', today())
                    ->whereNull('punch_out')
                    ->get();
                $pendingAttendances = $pendingAttendances->merge($todayAttendances);
            }

            foreach ($pendingAttendances as $attendance) {
                $carbonDate = Carbon::parse($attendance->date);
                $attendance->punch_out = $closingTime;
                $attendance->total_hours = Carbon::parse($attendance->punch_in)
                    ->diff(Carbon::parse($closingTime))
                    ->format('%H:%I:%S');
                $attendance->save();
            }

            $datesToCheck = [Carbon::yesterday()->toDateString(), Carbon::today()->toDateString()];

            foreach ($datesToCheck as $date) {
                if ($currentTime > $closingTime || $date == Carbon::yesterday()->toDateString()) {
                    $absentUsers = User::whereDoesntHave('attendances', function ($query) use ($date) {
                        $query->whereDate('date', $date);
                    })->get();

                    foreach ($absentUsers as $user) {
                        Attendance::create([
                            'user_id' => $user->id,
                            'date' => $date,
                            'status' => 'absent',
                        ]);
                    }
                }
            }
        }

        $office_open_time = Carbon::createFromFormat('H:i:s', $settings->office_open_time ?? '09:00:00')->format('h:i A');
        $office_close_time = Carbon::createFromFormat('H:i:s', $settings->office_close_time ?? '18:00:00')->format('h:i A');
        $late_threshold = $settings->late_threshold ?? 15;
        $half_day_threshold = Carbon::createFromFormat('H:i:s', $settings->half_day_threshold ?? '12:00:00')->format('h:i A');
        $break_end_time = Carbon::createFromFormat('H:i:s', $settings->break_end_time ?? '14:00:00')->format('h:i A');
        $early_out_threshold = Carbon::createFromFormat('H:i:s', $settings->early_out_threshold ?? $settings->office_close_time)->format('h:i A');

        $late_deduction_threshold = $settings->late_deduction_threshold ?? 3;
        $half_day_deduction_threshold = $settings->half_day_deduction_threshold ?? 2;

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
            $currentPortfolio = Project::whereHas('accountManagers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where("status", "Current")
                ->selectRaw("SUM(contract_value / NULLIF(contract_term, 0)) as total")
                ->value("total");

            $campaignCounts = Project::whereHas('accountManagers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->selectRaw("status, COUNT(*) as count")
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status');

            $pendingTasks = Task::where('assigned_to', $user->id)->whereIn('status', ['Todo', 'In Progress', 'Review'])->get();
            $completedTasks = Task::where('assigned_to', $user->id)->where('status', 'Completed')->get();
            $assignedTasks = Task::where('created_by', $user->id)->get();

            $accountProjects = Project::whereHas('accountManagers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();

            $extraData += [
                'currentPortfolio',
                'projects',
                'campaignCounts',
                'pendingTasks',
                'completedTasks',
                'assignedTasks',
                'accountProjects',
            ];
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

            $pendingTasks = Task::whereIn('status', ['Todo', 'In Progress', 'Review'])->where('assigned_to', $user->id)->get();
            $completedTasks = Task::where('status', 'Completed')->where('assigned_to', $user->id)->get();
            $pendingTasksCount = Task::whereIn('status', ['Todo', 'In Progress', 'Review'])->where('assigned_to', $user->id)->count();
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
                    ->whereIn('status', ['Todo', 'In Progress', 'Review']);
            })
                ->where('field_name', 'number_of_words')
                ->sum('field_value');
            $pendingWords = $pendingWords ?? 0;
            $extraData += [
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
            ];
        } elseif ($role == 'ADMIN') {
            $this->assignMonthlyTasks();
            $this->contractRenewalCheck();
        }
        return view('dashboard', array_merge(
            compact(
                'role',
                'latestNotifications',
                'taskData',
                'taskPercentages',
                'pendingTasksCountGlobal',
                'completedTasksCountGlobal',
                'createdTasksCountGlobal',
                'collaboratorTasksCountGlobal',
                'office_open_time',
                'office_close_time',
                'late_threshold',
                'half_day_threshold',
                'break_end_time',
                'early_out_threshold',
                'late_deduction_threshold',
                'half_day_deduction_threshold',
                $extraData
            ),
        ));
    }

    public function filterAttendance(Request $request)
    {
        $date = $request->date ?? Carbon::today()->toDateString();
        $settings = GeneralSetting::first();
        $holidays = $settings->holidays ?? [];
        $carbonDate = Carbon::parse($date);

        if (in_array($date, $holidays) || $carbonDate->isWeekend()) {
            return response()->json(['error' => 'No attendance records on holidays or weekends.'], 400);
        }

        $attendances = Attendance::with('user')
            ->whereDate('date', $date)
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($attendance) {
                // Use the 'status' field from the database instead of calculating it
                $attendance->status_labels = $attendance->status ?? 'Pending';
                return $attendance;
            });

        return response()->json(['attendance' => $attendances]);
    }
    public function assignMonthlyTasks()
    {
        $automation = Automation::firstOrCreate(['key' => 'monthly_tasks_assigned']);

        if ($automation->last_run_date && Carbon::parse($automation->last_run_date)->isSameMonth(now())) {
            return;
        }

        $activeProjects = Project::whereNotIn('status', ['Completed', 'Cancelled', 'Expired'])->get();

        if ($activeProjects->isEmpty()) return;

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
                    'start_date' => Carbon::now()->startOfMonth(),
                    'due_date' => Carbon::now()->startOfMonth()->addDays(3),
                    'created_by' => 1,
                    'is_automated' => 1,
                    'project_id' => $project->id,
                    'type' => 'ADHOC'
                ]);
            }
        }
        $automation->last_run_date = now();
        $automation->save();
    }
    public function contractRenewalCheck()
    {
        $automation = Automation::firstOrCreate(
            ['key' => 'contract_renewal_check'],
            ['last_run_date' => null]
        );

        $now = Carbon::now();

        if (!$automation->last_run_date || !$automation->last_run_date->isSameDay($now)) {
            $projects = Project::whereNotIn('status', ['Completed', 'Cancelled', 'Expired'])->get();
            $today = $now->startOfDay();

            foreach ($projects as $project) {
                $accountManager = $project->accountManagers->pluck('id')->first();

                $contractStart = Carbon::parse($project->contract_start_date);
                $contractEnd = $contractStart->addMonths($project->contract_term)->startOfDay();

                if ($contractEnd->equalTo($today)) {
                    $project->update(['status' => 'Expired']);
                }

                $reminderDate = $contractEnd->copy()->subMonth();
                $contractDate = $contractEnd->copy();
                $statusDate = $contractEnd->copy();

                $this->assignRenewalTask($project, $accountManager, "Renewal Reminder", $reminderDate);
                $this->assignRenewalTask($project, $accountManager, "Renewal Contract", $contractDate);
                $this->assignRenewalTask($project, $accountManager, "Renewal Status", $statusDate);
            }

            $automation->update(['last_run_date' => $now]);
        }
    }
    public function assignRenewalTask($project, $assignedTo, $title, $date)
    {
        if (Carbon::now()->greaterThanOrEqualTo($date)) {
            Task::firstOrCreate([
                'title' => $title,
                'project_id' => $project->id,
                'assigned_to' => $assignedTo,
                'start_date' => $date,
                'type' => 'ADHOC',
                'is_automated' => 1
            ], [
                'description' => "Renewal Reminder Task",
                'status' => 'Todo',
                'due_date' => Carbon::now()->startOfMonth()->addDays(3),
                'created_by' => 1,
            ]);
        }
    }
}
