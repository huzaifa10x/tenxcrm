<?php

namespace App\Http\Controllers;

use App\Models\AdminTask;
use App\Models\AmTask;
use App\Models\Attendance;
use App\Models\Automation;
use App\Models\CancellationReason;
use App\Models\ContentTask;
use App\Models\DesignTask;
use App\Models\DevelopmentTask;
use App\Models\GeneralSetting;
use App\Models\Message;
use App\Models\OffsiteTask;
use App\Models\OnsiteTask;
use App\Models\PartnerLink;
use App\Models\Project;
use App\Models\ProjectKeyword;
use App\Models\ProjectRenewal;
use App\Models\RepeatingTask;
use App\Models\SalesTask;
use App\Models\SeoAdminTask;
use App\Models\Task;
use App\Models\TaskAttachment;
use App\Models\TaskCompletion;
use App\Models\User;
use App\Models\WebsiteProject;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{

    // public function index(Request $request)
    // {
    //     $user = auth()->user();
    //     $extraData = [];
    //     $role = $user->roles->first()->name;
    //     $latestNotifications = $user->notifications()->latest()->take(5)->get();

    //     $repeatingTasks = RepeatingTask::all();
    //     $now = Carbon::now('Asia/Dubai');
    //     $dates = collect(range(6, 0))->map(function ($i) {
    //         return Carbon::today()->subDays($i)->format('Y-m-d');
    //     });
    //     $closedTasksPerDay = Task::completed()
    //         ->select(DB::raw('DATE(completed_date) as date'), DB::raw('count(*) as count'))
    //         ->where('assigned_to', $user->id)
    //         ->whereBetween('completed_date', [Carbon::now()->subDays(6), Carbon::today()])
    //         ->groupBy('date')
    //         ->pluck('count', 'date');

    //     $taskStats = $dates->map(function ($date) use ($closedTasksPerDay) {
    //         return $closedTasksPerDay[$date] ?? 0;
    //     });

    //     $yesterday = Carbon::yesterday();
    //     $last7Days = Carbon::now()->subDays(7);
    //     $last30Days = Carbon::now()->subDays(30);

    //     $tasksClosedYesterday = Task::completed()
    //         ->whereDate('completed_date', $yesterday)
    //         ->where('assigned_to', $user->id)
    //         ->count();

    //     $tasksClosedLast7Days = Task::completed()
    //         ->whereDate('completed_date', '>=', $last7Days)
    //         ->where('assigned_to', $user->id)
    //         ->count();

    //     $tasksClosedLast30Days = Task::completed()
    //         ->whereDate('completed_date', '>=', $last30Days)
    //         ->where('assigned_to', $user->id)
    //         ->count();

    //     foreach ($repeatingTasks as $repeatingTask) {
    //         if ($repeatingTask->shouldRepeat()) {
    //             $nextDueDate = Carbon::parse($repeatingTask->start_from);
    //             if ($repeatingTask->repeat_every == 'week') {
    //                 $nextDueDate->addWeeks($repeatingTask->completed_repeats * $repeatingTask->count);
    //             } elseif ($repeatingTask->repeat_every == 'month') {
    //                 $nextDueDate->addMonths($repeatingTask->completed_repeats * $repeatingTask->count);
    //             }
    //             $newRecurringTask = Task::create([
    //                 'is_automated' => 1,
    //                 'title' => $repeatingTask->task->title,
    //                 'description' => $repeatingTask->task->description,
    //                 'project_id' => $repeatingTask->task->project_id,
    //                 'department_id' => $repeatingTask->task->department_id,
    //                 'created_by' => $repeatingTask->task->created_by,
    //                 'assigned_to' => $repeatingTask->task->assigned_to,
    //                 'priority' => $repeatingTask->task->priority,
    //                 'type' => $repeatingTask->task->type,
    //                 'status' => 'Todo',
    //                 'due_date' => $nextDueDate,
    //                 'start_date' => $nextDueDate,
    //                 'repeat_task' => 0,
    //                 'parent_task_id' => $repeatingTask->task->id,
    //             ]);
    //             if ($repeatingTask->task->attachments()->exists()) {
    //                 foreach ($repeatingTask->task->attachments as $file) {
    //                     TaskAttachment::create([
    //                         'task_id' => $newRecurringTask->id,
    //                         'file_path' => $file->file_path,
    //                         'user_id' => auth()->id(),
    //                         'original_name' => $file->original_name,
    //                     ]);
    //                 }
    //             }
    //             if ($repeatingTask->task->comments()->exists()) {
    //                 foreach ($repeatingTask->task->comments as $comment) {
    //                     $newRecurringTask->comments()->create([
    //                         'task_id' => $newRecurringTask->id,
    //                         'user_id' => $comment->user_id,
    //                         'comment' => $comment->comment,
    //                     ]);
    //                 }
    //             }
    //             if ($repeatingTask->task->history()->exists()) {
    //                 foreach ($repeatingTask->task->history as $history) {
    //                     $newRecurringTask->history()->create([
    //                         'task_id' => $newRecurringTask->id,
    //                         'user_id' => $history->user_id,
    //                         'action' => $history->action,
    //                     ]);
    //                 }
    //             }
    //             if ($repeatingTask->task->collaborators()->exists()) {
    //                 foreach ($repeatingTask->task->collaborators as $collaborator) {
    //                     $newRecurringTask->collaborators()->create([
    //                         'task_id' => $newRecurringTask->id,
    //                         'user_id' => $collaborator->user_id,
    //                     ]);
    //                 }
    //             }
    //             $this->createTaskTypeEntry($repeatingTask->task, $newRecurringTask->id);
    //             $repeatingTask->completed_repeats += 1;
    //             $repeatingTask->save();
    //         }
    //     }

    //     $settings = GeneralSetting::first();

    //     if ($settings) {
    //         $closingTime = Carbon::parse($settings->office_close_time)->toTimeString();
    //         $currentTime = now()->toTimeString();
    //         $pendingAttendances = Attendance::where('date', '<', today())
    //             ->whereNull('punch_out')
    //             ->get();
    //         if ($currentTime > $closingTime) {
    //             $todayAttendances = Attendance::where('date', today())
    //                 ->whereNull('punch_out')
    //                 ->get();
    //             $pendingAttendances = $pendingAttendances->merge($todayAttendances);
    //         }

    //         foreach ($pendingAttendances as $attendance) {
    //             $carbonDate = Carbon::parse($attendance->date);
    //             $attendance->punch_out = $closingTime;

    //             $attendance->total_hours = Carbon::parse($attendance->punch_in)
    //                 ->diff(Carbon::parse($closingTime))
    //                 ->format('%H:%I:%S');
    //             $attendance->save();
    //         }

    //         $datesToCheck = [Carbon::yesterday()->toDateString(), Carbon::today()->toDateString()];

    //         foreach ($datesToCheck as $date) {
    //             if ($currentTime > $closingTime || $date == Carbon::yesterday()->toDateString()) {
    //                 $absentUsers = User::whereDoesntHave('attendances', function ($query) use ($date) {
    //                     $query->whereDate('date', $date);
    //                 })->get();

    //                 foreach ($absentUsers as $user) {
    //                     $isWeekend = Carbon::parse($date)->isWeekend();
    //                     $status = $isWeekend ? 'holiday' : 'absent';

    //                     Attendance::create([
    //                         'user_id' => $user->id,
    //                         'date' => $date,
    //                         'status' => $status,
    //                     ]);
    //                 }
    //             }
    //         }
    //     }

    //     $office_open_time = Carbon::createFromFormat('H:i:s', $settings->office_open_time ?? '09:00:00')->format('h:i A');
    //     $office_close_time = Carbon::createFromFormat('H:i:s', $settings->office_close_time ?? '18:00:00')->format('h:i A');
    //     $late_threshold = $settings->late_threshold ?? 15;
    //     $half_day_threshold = Carbon::createFromFormat('H:i:s', $settings->half_day_threshold ?? '12:00:00')->format('h:i A');
    //     $break_end_time = Carbon::createFromFormat('H:i:s', $settings->break_end_time ?? '14:00:00')->format('h:i A');
    //     $early_out_threshold = Carbon::createFromFormat('H:i:s', $settings->early_out_threshold ?? $settings->office_close_time)->format('h:i A');

    //     $late_deduction_threshold = $settings->late_deduction_threshold ?? 3;
    //     $half_day_deduction_threshold = $settings->half_day_deduction_threshold ?? 2;

    //     $last15Days = collect(range(0, 14))->map(function ($day) {
    //         return Carbon::now()->subDays($day)->format('Y-m-d');
    //     })->reverse()->values();

    //     $tasksLast15Days = Task::where('assigned_to', $user->id)
    //         ->whereBetween('created_at', [Carbon::now()->subDays(14)->startOfDay(), Carbon::now()->endOfDay()])
    //         ->selectRaw("DATE(created_at) as date, COUNT(*) as count")
    //         ->groupBy('date')
    //         ->pluck('count', 'date');

    //     $taskData = $last15Days->mapWithKeys(function ($date) use ($tasksLast15Days) {
    //         return [$date => $tasksLast15Days[$date] ?? 0];
    //     });

    //     $filter = $request->query('filter', 'today');
    //     if ($filter === 'monthly') {
    //         $startDate = Carbon::now()->subMonth()->startOfMonth();
    //     } elseif ($filter === 'weekly') {
    //         $startDate = Carbon::now()->subWeek()->startOfWeek();
    //     } elseif ($filter === 'today') {
    //         $startDate = Carbon::now()->startOfDay();
    //     } else {
    //         $startDate = Carbon::now()->subYear()->startOfYear();
    //     }
    //     $endDate = Carbon::now()->endOfDay();
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
    //     $collaboratorTasksCountGlobal = Task::whereExists(function ($subQuery) use ($user) {
    //         $subQuery->select(DB::raw(1))
    //             ->from('task_collaborators')
    //             ->whereRaw('task_collaborators.task_id = tasks.id')
    //             ->where('task_collaborators.user_id', $user->id);
    //     })->whereBetween('created_at', [$startDate, $endDate])
    //         ->count();
    //     $totalTasks = $pendingTasksCountGlobal + $completedTasksCountGlobal + $createdTasksCountGlobal + $collaboratorTasksCountGlobal;
    //     $totalTasks = max($totalTasks, 1);
    //     $taskPercentages = [
    //         'pending' => round(($pendingTasksCountGlobal / $totalTasks) * 100),
    //         'completed' => round(($completedTasksCountGlobal / $totalTasks) * 100),
    //         'created' => round(($createdTasksCountGlobal / $totalTasks) * 100),
    //         'collaborator' => round(($collaboratorTasksCountGlobal / $totalTasks) * 100),
    //     ];
    //     if ($request->ajax()) {
    //         return response()->json([
    //             'pendingTasksCountGlobal' => $pendingTasksCountGlobal,
    //             'completedTasksCountGlobal' => $completedTasksCountGlobal,
    //             'createdTasksCountGlobal' => $createdTasksCountGlobal,
    //             'collaboratorTasksCountGlobal' => $collaboratorTasksCountGlobal,
    //             'taskPercentages' => $taskPercentages,
    //         ]);
    //     }
    //     if ($role == 'AM') {
    //         $projects = Project::whereHas('accountManagers', function ($query) use ($user) {
    //             $query->where('user_id', $user->id);
    //         })->get();

    //         $totalRenewals = ProjectRenewal::whereIn('project_id', $projects->pluck('id'))->count();

    //         // Partner Links Start
    //         $monthlyStats = DB::table('partner_links')
    //             ->selectRaw('MONTH(created_at) as month_number, COUNT(*) as total_links, SUM(amount) as total_amount')
    //             ->whereIn('project_id', $projects->pluck('id')) // limit to AM's projects
    //             ->groupBy(DB::raw('MONTH(created_at)'))
    //             ->orderBy('month_number')
    //             ->get();

    //         // Fill missing months with zero
    //         $monthlyData = collect(range(1, 12))->map(function ($month) use ($monthlyStats) {
    //             $stat = $monthlyStats->firstWhere('month_number', $month);
    //             return [
    //                 'month' => Carbon::create()->month($month)->format('M'),
    //                 'links' => $stat->total_links ?? 0,
    //                 'amount' => $stat->total_amount ?? 0,
    //             ];
    //         });
    //         // Partner Links End

    //         $currentPortfolio = Project::whereHas('accountManagers', function ($query) use ($user) {
    //             $query->where('user_id', $user->id);
    //         })->where("status", "Current")
    //             ->selectRaw("SUM(contract_value / NULLIF(contract_term, 0)) as total")
    //             ->value("total");

    //         $baseNewSellQuery = Project::whereHas('salesManagers', function ($query) use ($user) {
    //             $query->where('user_id', $user->id);
    //         })
    //             ->where('status', 'Current')
    //             ->whereBetween('contract_start_date', [
    //                 Carbon::now()->subMonth()->startOfMonth(),
    //                 Carbon::now()->endOfMonth(),
    //             ]);

    //         $newSell = (clone $baseNewSellQuery)
    //             ->selectRaw('SUM(contract_value / NULLIF(contract_term, 0)) as total')
    //             ->value('total') ?? 0;

    //         $newSellProjects = (clone $baseNewSellQuery)->get();

    //         $accountManagerId = $user->id;

    //         $accountManagers = User::whereHas('roles', fn($q) => $q->where('name', 'AM'))->get();

    //         $query = Project::with(['salesManagers', 'accountManagers', 'techManagers', 'seoTechs', 'contentWriters', 'seoAdmins']);

    //         $statusCounts = [];
    //         $typeCounts   = [];

    //         $statusCounts = Project::whereHas('accountManagers', function ($q) use ($accountManagerId) {
    //             $q->where('user_id', $accountManagerId);
    //         })
    //             ->selectRaw('status, count(*) as count')
    //             ->groupBy('status')
    //             ->pluck('count', 'status');

    //         $typeCounts = Project::whereHas('accountManagers', function ($q) use ($accountManagerId) {
    //             $q->where('user_id', $accountManagerId);
    //         })
    //             ->selectRaw('project_type, count(*) as count')
    //             ->groupBy('project_type')
    //             ->pluck('count', 'project_type');

    //         $pendingTasks = Task::where('assigned_to', $user->id)->whereIn('status', ['Todo', 'In Progress', 'Review'])
    //             ->whereHas('project', function ($q) {
    //                 $q->whereIn('status', ['Current', 'WFF', 'Debt Collection']);
    //             })
    //             ->get();
    //         $completedTasks = Task::where('assigned_to', $user->id)->where('status', 'Completed')->get();
    //         $assignedTasks = Task::where('created_by', $user->id)->get();

    //         $accountProjects = Project::whereHas('accountManagers', function ($query) use ($user) {
    //             $query->where('user_id', $user->id);
    //         })->get();

    //         $extraData += [
    //             'currentPortfolio',
    //             'projects',
    //             'pendingTasks',
    //             'completedTasks',
    //             'assignedTasks',
    //             'accountProjects',
    //             'statusCounts',
    //             'typeCounts',
    //             'newSell',
    //             'newSellProjects',
    //             'monthlyStats',
    //             'monthlyData',
    //             'totalRenewals',
    //         ];
    //     } elseif ($role == 'CONTENT') {
    //         $projects = Project::whereHas('contentWriters', function ($query) use ($user) {
    //             $query->where('user_id', $user->id);
    //         })->get();
    //         $currentPortfolio = $projects->sum(function ($project) {
    //             return ($project->contract_value && $project->contract_term) ?
    //                 ($project->contract_value / $project->contract_term) : 0;
    //         });
    //         $campaignCounts = Project::whereHas('contentWriters', function ($query) use ($user) {
    //             $query->where('user_id', $user->id);
    //         })->selectRaw("status, COUNT(*) as count")
    //             ->groupBy('status')
    //             ->get()
    //             ->pluck('count', 'status');

    //         $pendingTasks = Task::whereIn('status', ['Todo', 'In Progress', 'Review'])->where('assigned_to', $user->id)->get();
    //         $completedTasks = Task::where('status', 'Completed')->where('assigned_to', $user->id)->get();
    //         $pendingTasksCount = Task::whereIn('status', ['Todo', 'In Progress', 'Review'])->where('assigned_to', $user->id)->count();
    //         $completedTasksCount = Task::where('status', 'Completed')->where('assigned_to', $user->id)->count();
    //         $totalTasksCount = Task::where('assigned_to', $user->id)->count();
    //         $assignedTasks = Task::where('created_by', $user->id)->get();
    //         $accountProjects = Project::whereHas('contentWriters', function ($query) use ($user) {
    //             $query->where('user_id', $user->id);
    //         })->get();
    //         $completedWords = TaskCompletion::whereHas('task', function ($query) use ($projects) {
    //             $query->whereIn('project_id', $projects->pluck('id'))
    //                 ->where('status', 'Completed');
    //         })
    //             ->where('field_name', 'number_of_words')
    //             ->sum('field_value');
    //         $pendingWords = TaskCompletion::whereHas('task', function ($query) use ($projects) {
    //             $query->whereIn('project_id', $projects->pluck('id'))
    //                 ->whereIn('status', ['Todo', 'In Progress', 'Review']);
    //         })
    //             ->where('field_name', 'number_of_words')
    //             ->sum('field_value');
    //         $pendingWords = $pendingWords ?? 0;
    //         $extraData += [
    //             'currentPortfolio',
    //             'projects',
    //             'campaignCounts',
    //             'pendingTasks',
    //             'completedTasks',
    //             'assignedTasks',
    //             'accountProjects',
    //             'pendingTasksCount',
    //             'completedTasksCount',
    //             'totalTasksCount',
    //             'completedWords',
    //             'pendingWords',
    //         ];
    //     } elseif ($role == 'ADMIN') {
    //         $projects = Project::whereIn('status', ['Current', 'WFF']);
    //         $WebsiteProjects = WebsiteProject::with(['project', 'developer', 'leadDeveloper'])->get();
    //         $pendingCancellations = CancellationReason::where('approved', false)->get();
    //         // Partner Links Start
    //         $accountManagers = User::whereHas('roles', function ($q) {
    //             $q->where('name', 'AM'); // Adjust if you use Spatie or similar
    //         })->get();

    //         $partnerLinksByAM = [];

    //         foreach ($accountManagers as $am) {
    //             $projects = Project::whereIn('status', ['Current', 'WFF'])
    //                 ->whereHas('accountManagers', function ($q) use ($am) {
    //                     $q->where('user_id', $am->id);
    //                 })
    //                 ->pluck('id');

    //             $monthlyStats = DB::table('partner_links')
    //                 ->selectRaw('MONTH(created_at) as month_number, COUNT(*) as total_links, SUM(amount) as total_amount')
    //                 ->whereIn('project_id', $projects)
    //                 ->groupBy(DB::raw('MONTH(created_at)'))
    //                 ->orderBy('month_number')
    //                 ->get();

    //             $monthlyData = collect(range(1, 12))->map(function ($month) use ($monthlyStats) {
    //                 $stat = $monthlyStats->firstWhere('month_number', $month);
    //                 return [
    //                     'month' => Carbon::create()->month($month)->format('M'),
    //                     'links' => $stat->total_links ?? 0,
    //                     'amount' => $stat->total_amount ?? 0,
    //                 ];
    //             });

    //             $partnerLinksByAM[] = [
    //                 'am_name' => $am->name,
    //                 'data' => $monthlyData,
    //             ];
    //         }
    //         // Partner Links End
    //         $this->assignMonthlyTasks();
    //         $this->contractRenewalCheck();
    //         if (now()->isStartOfMonth()) {
    //             $cacheKey = 'late_warnings_sent_' . now()->format('Y_m');
    //             if (!Cache::has($cacheKey)) {
    //                 $this->sendLateWarnings();
    //                 Cache::put($cacheKey, true, now()->endOfDay());
    //             }
    //         }
    //         $extraData += [
    //             'partnerLinksByAM',
    //             'pendingCancellations',
    //             'WebsiteProjects',
    //         ];
    //     }
    //     return view('dashboard', array_merge(
    //         compact(
    //             'role',
    //             'latestNotifications',
    //             'taskData',
    //             'taskPercentages',
    //             'pendingTasksCountGlobal',
    //             'completedTasksCountGlobal',
    //             'createdTasksCountGlobal',
    //             'collaboratorTasksCountGlobal',
    //             'office_open_time',
    //             'office_close_time',
    //             'late_threshold',
    //             'half_day_threshold',
    //             'break_end_time',
    //             'early_out_threshold',
    //             'late_deduction_threshold',
    //             'half_day_deduction_threshold',
    //             'dates',
    //             'taskStats',
    //             'tasksClosedYesterday',
    //             'tasksClosedLast7Days',
    //             'tasksClosedLast30Days',
    //             $extraData
    //         ),
    //     ));
    // }

    public function index(Request $request)
    {
        $user = auth()->user();
        $extraData = [];
        $role = $user->roles->first()->name;
        $latestNotifications = $user->notifications()->latest()->take(5)->get();
        $accountManagersForFilter=[];

        $repeatingTasks = RepeatingTask::all();
        $now = Carbon::now('Asia/Dubai');
        $dates = collect(range(6, 0))->map(function ($i) {
            return Carbon::today()->subDays($i)->format('Y-m-d');
        });
        $closedTasksPerDay = Task::completed()
            ->select(DB::raw('DATE(completed_date) as date'), DB::raw('count(*) as count'))
            ->where('assigned_to', $user->id)
            ->whereBetween('completed_date', [Carbon::now()->subDays(6), Carbon::today()])
            ->groupBy('date')
            ->pluck('count', 'date');

        $taskStats = $dates->map(function ($date) use ($closedTasksPerDay) {
            return $closedTasksPerDay[$date] ?? 0;
        });

        $yesterday = Carbon::yesterday();
        $last7Days = Carbon::now()->subDays(7);
        $last30Days = Carbon::now()->subDays(30);

        $tasksClosedYesterday = Task::completed()
            ->whereDate('completed_date', $yesterday)
            ->where('assigned_to', $user->id)
            ->count();

        $tasksClosedLast7Days = Task::completed()
            ->whereDate('completed_date', '>=', $last7Days)
            ->where('assigned_to', $user->id)
            ->count();

        $tasksClosedLast30Days = Task::completed()
            ->whereDate('completed_date', '>=', $last30Days)
            ->where('assigned_to', $user->id)
            ->count();

        foreach ($repeatingTasks as $repeatingTask) {
            if ($repeatingTask->shouldRepeat()) {
                $nextDueDate = Carbon::parse($repeatingTask->start_from);
                if ($repeatingTask->repeat_every == 'week') {
                    $nextDueDate->addWeeks($repeatingTask->completed_repeats * $repeatingTask->count);
                } elseif ($repeatingTask->repeat_every == 'month') {
                    $nextDueDate->addMonths($repeatingTask->completed_repeats * $repeatingTask->count);
                }
                $newRecurringTask = Task::create([
                    'is_automated' => 1,
                    'title' => $repeatingTask->task->title,
                    'description' => $repeatingTask->task->description,
                    'project_id' => $repeatingTask->task->project_id,
                    'department_id' => $repeatingTask->task->department_id,
                    'created_by' => $repeatingTask->task->created_by,
                    'assigned_to' => $repeatingTask->task->assigned_to,
                    'priority' => $repeatingTask->task->priority,
                    'type' => $repeatingTask->task->type,
                    'status' => 'Todo',
                    'due_date' => $nextDueDate,
                    'start_date' => $nextDueDate,
                    'repeat_task' => 0,
                    'parent_task_id' => $repeatingTask->task->id,
                ]);
                if ($repeatingTask->task->attachments()->exists()) {
                    foreach ($repeatingTask->task->attachments as $file) {
                        TaskAttachment::create([
                            'task_id' => $newRecurringTask->id,
                            'file_path' => $file->file_path,
                            'user_id' => auth()->id(),
                            'original_name' => $file->original_name,
                        ]);
                    }
                }
                if ($repeatingTask->task->comments()->exists()) {
                    foreach ($repeatingTask->task->comments as $comment) {
                        $newRecurringTask->comments()->create([
                            'task_id' => $newRecurringTask->id,
                            'user_id' => $comment->user_id,
                            'comment' => $comment->comment,
                        ]);
                    }
                }
                if ($repeatingTask->task->history()->exists()) {
                    foreach ($repeatingTask->task->history as $history) {
                        $newRecurringTask->history()->create([
                            'task_id' => $newRecurringTask->id,
                            'user_id' => $history->user_id,
                            'action' => $history->action,
                        ]);
                    }
                }
                if ($repeatingTask->task->collaborators()->exists()) {
                    foreach ($repeatingTask->task->collaborators as $collaborator) {
                        $newRecurringTask->collaborators()->create([
                            'task_id' => $newRecurringTask->id,
                            'user_id' => $collaborator->user_id,
                        ]);
                    }
                }
                $this->createTaskTypeEntry($repeatingTask->task, $newRecurringTask->id);
                $repeatingTask->completed_repeats += 1;
                $repeatingTask->save();
            }
        }

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
                        $isWeekend = Carbon::parse($date)->isWeekend();
                        $status = $isWeekend ? 'holiday' : 'absent';

                        Attendance::create([
                            'user_id' => $user->id,
                            'date' => $date,
                            'status' => $status,
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

            $totalRenewals = ProjectRenewal::whereIn('project_id', $projects->pluck('id'))->count();

            // Partner Links Start
            $monthlyStats = DB::table('partner_links')
                ->selectRaw('MONTH(created_at) as month_number, COUNT(*) as total_links, SUM(amount) as total_amount')
                ->whereIn('project_id', $projects->pluck('id')) // limit to AM's projects
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->orderBy('month_number')
                ->get();

            // Fill missing months with zero
            $monthlyData = collect(range(1, 12))->map(function ($month) use ($monthlyStats) {
                $stat = $monthlyStats->firstWhere('month_number', $month);
                return [
                    'month' => Carbon::create()->month($month)->format('M'),
                    'links' => $stat->total_links ?? 0,
                    'amount' => $stat->total_amount ?? 0,
                ];
            });
            // Partner Links End

            $currentPortfolio = Project::whereHas('accountManagers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where("status", "Current")
                ->selectRaw("SUM(contract_value / NULLIF(contract_term, 0)) as total")
                ->value("total");

            $baseNewSellQuery = Project::whereHas('salesManagers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
                ->where('status', 'Current')
                ->whereBetween('contract_start_date', [
                    Carbon::now()->subMonth()->startOfMonth(),
                    Carbon::now()->endOfMonth(),
                ]);

            $newSell = (clone $baseNewSellQuery)
                ->selectRaw('SUM(contract_value / NULLIF(contract_term, 0)) as total')
                ->value('total') ?? 0;

            $newSellProjects = (clone $baseNewSellQuery)->get();

            $accountManagerId = $user->id;

            $accountManagers = User::whereHas('roles', fn($q) => $q->where('name', 'AM'))->get();

            $query = Project::with(['salesManagers', 'accountManagers', 'techManagers', 'seoTechs', 'contentWriters', 'seoAdmins']);

            $statusCounts = [];
            $typeCounts   = [];

            $statusCounts = Project::whereHas('accountManagers', function ($q) use ($accountManagerId) {
                $q->where('user_id', $accountManagerId);
            })
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');

            $typeCounts = Project::whereHas('accountManagers', function ($q) use ($accountManagerId) {
                $q->where('user_id', $accountManagerId);
            })
                ->selectRaw('project_type, count(*) as count')
                ->groupBy('project_type')
                ->pluck('count', 'project_type');

            $pendingTasks = Task::where('assigned_to', $user->id)->whereIn('status', ['Todo', 'In Progress', 'Review'])
                ->whereHas('project', function ($q) {
                    $q->whereIn('status', ['Current', 'WFF', 'Debt Collection']);
                })
                ->get();
            $completedTasks = Task::where('assigned_to', $user->id)->where('status', 'Completed')->get();
            $assignedTasks = Task::where('created_by', $user->id)->get();

            $accountProjects = Project::whereHas('accountManagers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();

            $extraData += [
                'currentPortfolio',
                'projects',
                'pendingTasks',
                'completedTasks',
                'assignedTasks',
                'accountProjects',
                'statusCounts',
                'typeCounts',
                'newSell',
                'newSellProjects',
                'monthlyStats',
                'monthlyData',
                'totalRenewals',
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
        } elseif ($role == 'ADMIN' || $role == 'SEO ADMIN') {
            $projects = Project::whereIn('status', ['Current', 'WFF']);
            $WebsiteProjects = WebsiteProject::with(['project', 'developer', 'leadDeveloper'])->get();
            $pendingCancellations = CancellationReason::where('approved', false)->get();

            // SEO Revenue Calculations
            $seoProjects = Project::whereIn('project_type', ['SEO']);

            // Current SEO Revenue (Current status only)
            $currentSeoProjects = (clone $seoProjects)->where('status', 'Current')->get();
            $currentSeoRevenue = $currentSeoProjects->sum(function ($project) {
                return ($project->contract_value && $project->contract_term)
                    ? ($project->contract_value / $project->contract_term)
                    : 0;
            });
            $currentSeoProjectsCount = $currentSeoProjects->count();

            // WFF SEO Revenue
            $wffProjects = (clone $seoProjects)->where('status', 'WFF')->get();
            $wffRevenue = $wffProjects->sum(function ($project) {
                return ($project->contract_value && $project->contract_term)
                    ? ($project->contract_value / $project->contract_term)
                    : 0;
            });
            
            $wffProjectsCount = $wffProjects->count();

            // Total SEO Revenue (Current + WFF)
            $totalSeoRevenue = $currentSeoRevenue + $wffRevenue;
            $monthlySeoRevenue = $totalSeoRevenue / 30;

            // Get all SEO projects for table (with pagination)
            $seoProjectsQuery = Project::with(['accountManagers', 'salesManagers'])
                ->whereIn('project_type', ['SEO'])
                ->orderBy('created_at', 'desc');

            // Apply filters if present in request
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $seoProjectsQuery->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('client_name', 'like', "%{$search}%")
                        ->orWhere('website_url', 'like', "%{$search}%")
                        ->orWhere('client_email', 'like', "%{$search}%");
                });
            }

            if ($request->has('status') && $request->status) {
                $seoProjectsQuery->where('status', $request->status);
            }

            if ($request->has('project_type') && $request->project_type) {
                $seoProjectsQuery->where('project_type', $request->project_type);
            }

            if ($request->has('account_manager_id') && $request->account_manager_id) {
                $seoProjectsQuery->whereHas('accountManagers', function ($q) use ($request) {
                    $q->where('user_id', $request->account_manager_id);
                });
            }

            if ($request->has('start_date_from') && $request->start_date_from) {
                $seoProjectsQuery->where('contract_start_date', '>=', $request->start_date_from);
            }

            if ($request->has('start_date_to') && $request->start_date_to) {
                $seoProjectsQuery->where('contract_start_date', '<=', $request->start_date_to);
            }

            $seoProjectsList = $seoProjectsQuery->get();

            // Get all account managers for filter dropdown
            $accountManagersForFilter = User::whereHas('roles', fn($q) => $q->where('name', 'AM'))
                ->orderBy('name')
                ->get();

            // Partner Links Start (Only for ADMIN)
            $partnerLinksByAM = [];
            if ($role == 'ADMIN') {
                $accountManagers = User::whereHas('roles', function ($q) {
                    $q->where('name', 'AM');
                })->get();

                foreach ($accountManagers as $am) {
                    $amProjects = Project::whereIn('status', ['Current', 'WFF'])
                        ->whereHas('accountManagers', function ($q) use ($am) {
                            $q->where('user_id', $am->id);
                        })
                        ->pluck('id');

                    $monthlyStats = DB::table('partner_links')
                        ->selectRaw('MONTH(created_at) as month_number, COUNT(*) as total_links, SUM(amount) as total_amount')
                        ->whereIn('project_id', $amProjects)
                        ->groupBy(DB::raw('MONTH(created_at)'))
                        ->orderBy('month_number')
                        ->get();

                    $monthlyData = collect(range(1, 12))->map(function ($month) use ($monthlyStats) {
                        $stat = $monthlyStats->firstWhere('month_number', $month);
                        return [
                            'month' => Carbon::create()->month($month)->format('M'),
                            'links' => $stat->total_links ?? 0,
                            'amount' => $stat->total_amount ?? 0,
                        ];
                    });

                    $partnerLinksByAM[] = [
                        'am_name' => $am->name,
                        'data' => $monthlyData,
                    ];
                }
            }
            // Partner Links End

            if ($role == 'ADMIN') {
                $this->assignMonthlyTasks();
                $this->contractRenewalCheck();
                if (now()->isStartOfMonth()) {
                    $cacheKey = 'late_warnings_sent_' . now()->format('Y_m');
                    if (!Cache::has($cacheKey)) {
                        $this->sendLateWarnings();
                        Cache::put($cacheKey, true, now()->endOfDay());
                    }
                }
            }

            $extraData += [
                'partnerLinksByAM',
                'pendingCancellations',
                'WebsiteProjects',
                'totalSeoRevenue',
                'currentSeoRevenue',
                'currentSeoProjectsCount',
                'wffRevenue',
                'wffProjectsCount',
                'monthlySeoRevenue',
                // 'seoProjects' => $seoProjectsList,
                // 'accountManagers' => $accountManagersForFilter,
            ];
        }
        $accountManagers = $accountManagersForFilter;
        $seoProjects = $seoProjectsList;
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
                'dates',
                'taskStats',
                'tasksClosedYesterday',
                'tasksClosedLast7Days',
                'tasksClosedLast30Days',
                'accountManagers',
                'seoProjects',
                $extraData
            ),
        ));
    }

    // New method for filtering SEO projects via AJAX
    public function filterSeoProjects(Request $request)
    {
        // 1. Initialize the query WITHOUT ordering yet
        $seoProjectsQuery = Project::with(['accountManagers', 'salesManagers'])
            ->whereIn('project_type', ['SEO']);

        // 2. Apply all filters FIRST
        if ($request->filled('search')) {
            $search = $request->search;
            $seoProjectsQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('client_name', 'like', "%{$search}%")
                    ->orWhere('website_url', 'like', "%{$search}%")
                    ->orWhere('client_email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $seoProjectsQuery->where('status', $request->status);
        }

        if ($request->filled('project_type')) {
            $seoProjectsQuery->where('project_type', $request->project_type);
        }

        if ($request->filled('account_manager_id')) {
            $seoProjectsQuery->whereHas('accountManagers', function ($q) use ($request) {
                $q->where('user_id', $request->account_manager_id);
            });
        }

        if ($request->filled('start_date_from')) {
            $seoProjectsQuery->where('contract_start_date', '>=', $request->start_date_from);
        }

        if ($request->filled('start_date_to')) {
            $seoProjectsQuery->where('contract_start_date', '<=', $request->start_date_to);
        }

        // 3. Calculate Totals (Clone the query BEFORE adding orderBy or get)
        $totalContractValue = (clone $seoProjectsQuery)->sum('contract_value');

        // We use reorder() to strip the 'orderBy' which causes the 1140 error in some MySQL versions
        $totalMonthlyValue = (clone $seoProjectsQuery)
            ->reorder()
            ->selectRaw('SUM(contract_value / NULLIF(contract_term, 0)) as mrr')
            ->value('mrr') ?? 0;

        // 4. Now apply ordering and get the data for the table
        $projects = $seoProjectsQuery->orderBy('created_at', 'desc')->get()->map(function ($project) {
            return [
                'id' => $project->id,
                'title' => $project->title,
                'client_name' => $project->client_name,
                'client_email' => $project->client_email,
                'website_url' => $project->website_url,
                'status' => $project->status,
                'project_type' => $project->project_type,
                'contract_value' => $project->contract_value,
                'contract_term' => $project->contract_term,
                'monthly_value' => ($project->contract_value && $project->contract_term)
                    ? ($project->contract_value / $project->contract_term)
                    : 0,
                'contract_start_date' => $project->contract_start_date,
                'account_managers' => $project->accountManagers->map(function ($am) {
                    return ['name' => $am->name, 'id' => $am->id];
                })
            ];
        });

        return response()->json([
            'projects' => $projects,
            'total' => $projects->count(),
            'totalRevenue' => number_format($totalContractValue, 2, '.', ''),
            'monthlyRevenue' => number_format($totalMonthlyValue, 2, '.', '')
        ]);
    }

    // New method for exporting SEO projects
    public function exportSeoProjects(Request $request)
    {
        $seoProjectsQuery = Project::with(['accountManagers', 'salesManagers'])
            ->whereIn('project_type', ['SEO'])
            ->orderBy('created_at', 'desc');

        // Apply same filters as filterSeoProjects
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $seoProjectsQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('client_name', 'like', "%{$search}%")
                    ->orWhere('website_url', 'like', "%{$search}%")
                    ->orWhere('client_email', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status) {
            $seoProjectsQuery->where('status', $request->status);
        }

        if ($request->has('project_type') && $request->project_type) {
            $seoProjectsQuery->where('project_type', $request->project_type);
        }

        if ($request->has('account_manager_id') && $request->account_manager_id) {
            $seoProjectsQuery->whereHas('accountManagers', function ($q) use ($request) {
                $q->where('user_id', $request->account_manager_id);
            });
        }

        if ($request->has('start_date_from') && $request->start_date_from) {
            $seoProjectsQuery->where('contract_start_date', '>=', $request->start_date_from);
        }

        if ($request->has('start_date_to') && $request->start_date_to) {
            $seoProjectsQuery->where('contract_start_date', '<=', $request->start_date_to);
        }

        $projects = $seoProjectsQuery->get();

        // Create CSV
        $filename = 'seo_projects_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($projects) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Project Name',
                'Client Name',
                'Client Email',
                'Website URL',
                'Status',
                'Project Type',
                'Account Manager',
                'Monthly Value (AED)',
                'Total Value (AED)',
                'Contract Term (Months)',
                'Contract Start Date'
            ]);

            // CSV rows
            foreach ($projects as $project) {
                $accountManagers = $project->accountManagers->pluck('name')->join(', ');
                $monthlyValue = ($project->contract_value && $project->contract_term)
                    ? ($project->contract_value / $project->contract_term)
                    : 0;

                fputcsv($file, [
                    $project->title,
                    $project->companies->name ?? 'N/A',
                    $project->companies
                        ?->details
                        ?->first()
                        ?->email ?? 'N/A',
                    $project->companies->website ?? 'N/A',
                    $project->status,
                    $project->project_type,
                    $accountManagers ?: 'Not Assigned',
                    number_format($monthlyValue, 2),
                    number_format($project->contract_value, 2),
                    $project->contract_term,
                    $project->contract_start_date ? Carbon::parse($project->contract_start_date)->format('Y-m-d') : 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function createTaskTypeEntry($task, $newTaskID)
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
                $relatedData = OffsiteTask::where('task_id', $task->id)->first();
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
            case 'SEO ADMIN':
                $relatedData = SeoAdminTask::where('task_id', $task->id)->first();
                break;
            default:
                // Handle default if necessary
                $relatedData = null;
                break;
        }
        switch ($task->type) {
            case 'CONTENT':
                ContentTask::create([
                    'task_id' => $newTaskID,
                    'sub_type' => $relatedData->sub_type,
                    'number_of_pages' => $relatedData->number_of_pages,
                    'no_of_words' => $relatedData->no_of_words,
                    'closing_input' => $relatedData->closing_input,
                ]);
                break;

            case 'ONSITE':
                OnsiteTask::create([
                    'task_id' => $newTaskID,
                    'sub_type' => $relatedData->sub_type,
                    'closing_input' => $relatedData->closing_input,
                ]);
                break;

            case 'OFFSITE':
                $offsiteTask = OffsiteTask::create([
                    'task_id' => $newTaskID,
                    'sub_type' => $relatedData->sub_type,
                ]);
                $startingKeywords = ProjectKeyword::where('task_id', $task->id)->where('type', 'starting')->get();
                foreach ($startingKeywords as $data) {
                    ProjectKeyword::create([
                        'project_id' => $task->project_id,
                        'task_id' => $newTaskID,
                        'keyword' => $data->keyword,
                        'url' => $data->url,
                        'type' => 'starting',
                    ]);
                }
                break;

            case 'DESIGN':
                DesignTask::create([
                    'task_id' => $newTaskID,
                    'sub_type' => $relatedData->sub_type,
                    'input_number' => $relatedData->input_number,
                    'closing_input' => $relatedData->closing_input,
                ]);
                break;

            case 'DEVELOPMENT':
                DevelopmentTask::create([
                    'task_id' => $newTaskID,
                    'sub_type' => $relatedData->sub_type,
                    'closing_input' => $relatedData->closing_input,
                ]);
                break;

            case 'AM':
                AmTask::create([
                    'task_id' => $newTaskID,
                    'sub_type' => $relatedData->sub_type,
                ]);
                break;

            case 'SEO ADMIN':
                SeoAdminTask::create([
                    'task_id' => $newTaskID,
                    'sub_type' => 'ADHOC', // Assuming sub_type is 'ADHOC' for this case
                ]);
                break;

            case 'ADMIN':
                AdminTask::create([
                    'task_id' => $newTaskID,
                    'sub_type' => 'ADHOC', // Assuming sub_type is 'ADHOC' for this case
                ]);
                break;

            case 'SALES':
                SalesTask::create([
                    'task_id' => $newTaskID,
                    'sub_type' => 'ADHOC', // Assuming sub_type is 'ADHOC' for this case
                ]);
                break;

            default:
                // If no task type is matched, you can log an error or handle it differently
                break;
        }
    }
    public function chat(Request $request)
    {
        $prompt = $request->input('prompt');

        $apiKey = env('GROQ_API_KEY'); // Keep your key safe in .env

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $apiKey,
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => 'llama3-70b-8192',
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ]
        ]);

        return response()->json($response->json());
    }
    private function sendLateWarnings()
    {
        $apiKey = env('GROQ_API_KEY');
        $adminId = 1;
        $startDate = Carbon::now()->subMonths(2)->startOfMonth()->toDateString();
        $endDate = Carbon::now()->endOfMonth()->toDateString();
        $lateUsers = User::whereHas('attendances', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate])
                ->where('status', 'late');
        })
            ->with(['attendances' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate])
                    ->where('status', 'late');
            }])
            ->get();
        foreach ($lateUsers as $user) {
            $lateCount = $user->attendances->count();
            if ($lateCount > 1) {
                $prompt = "User {$user->name} has been late {$lateCount} times in the last 2 months. Generate a professional, but firm warning message in English to motivate punctuality, Sign it off with 'Muhammad Ali, 10x Digital'.";
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $apiKey,
                ])->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama3-70b-8192',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ]
                ]);
                $aiMessage = $response->json('choices.0.message.content') ?? 'You’ve received a late warning.';
                Message::create([
                    'sender_id' => $adminId,
                    'receiver_id' => $user->id,
                    'message' => $aiMessage,
                ]);
                DB::table('notifications')->insert([
                    'id' => \Str::uuid(),
                    'type' => \App\Notifications\CustomNotification::class,
                    'notifiable_type' => \App\Models\User::class,
                    'notifiable_id' => $user->id,
                    'data' => json_encode([
                        'title' => 'Late Warning Message',
                        'message' => $aiMessage,
                        'url' => route('messages.index'),
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return response()->json(['status' => 'Late warnings sent successfully.']);
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

                if (
                    $title === 'Campaign Meeting (Deep-dive) with Tech'
                    && $project->project_type === 'Google Ads'
                ) {
                    continue;
                }

                $existing = Task::where('title', $title)
                    ->where('project_id', $project->id)
                    ->whereMonth('start_date', now()->month)
                    ->whereYear('start_date', now()->year)
                    ->where('is_automated', 1)
                    ->exists();

                if ($existing) continue;

                Task::create([
                    'title' => $title,
                    'description' => $description,
                    'assigned_to' => $accountManager,
                    'status' => 'Todo',
                    'start_date' => Carbon::now()->startOfMonth(),
                    'due_date' => Carbon::now()->startOfMonth()->addDays(3),
                    'created_by' => User::where('email', 'system@10xdigital.ae')->value('id') ?? 1,
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
                // $this->assignRenewalTask($project, $accountManager, "Renewal Contract", $contractDate);
                // $this->assignRenewalTask($project, $accountManager, "Renewal Status", $statusDate);
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
