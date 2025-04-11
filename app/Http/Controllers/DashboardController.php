<?php

namespace App\Http\Controllers;

use App\Models\AdminTask;
use App\Models\AmTask;
use App\Models\Attendance;
use App\Models\Automation;
use App\Models\ContentTask;
use App\Models\DesignTask;
use App\Models\DevelopmentTask;
use App\Models\GeneralSetting;
use App\Models\OffsiteTask;
use App\Models\OnsiteTask;
use App\Models\Project;
use App\Models\ProjectKeyword;
use App\Models\RepeatingTask;
use App\Models\SalesTask;
use App\Models\SeoAdminTask;
use App\Models\Task;
use App\Models\TaskAttachment;
use App\Models\TaskCompletion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        $user = auth()->user();
        $extraData = [];
        $role = $user->roles->first()->name;
        $latestNotifications = $user->notifications()->latest()->take(5)->get();

        $repeatingTasks = RepeatingTask::all();
        $now = Carbon::now('Asia/Dubai');

        foreach ($repeatingTasks as $repeatingTask) {
            if ($repeatingTask->shouldRepeat()) {
                // Calculate the next due date based on the repeat interval (week/month)
                $nextDueDate = Carbon::parse($repeatingTask->start_from);

                if ($repeatingTask->repeat_every == 'week') {
                    $nextDueDate->addWeeks($repeatingTask->completed_repeats * $repeatingTask->count);
                } elseif ($repeatingTask->repeat_every == 'month') {
                    $nextDueDate->addMonths($repeatingTask->completed_repeats * $repeatingTask->count);
                }

                // Create a new recurring task
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

                // Attachments
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

                // Comments
                if ($repeatingTask->task->comments()->exists()) {
                    foreach ($repeatingTask->task->comments as $comment) {
                        $newRecurringTask->comments()->create([
                            'task_id' => $newRecurringTask->id,
                            'user_id' => $comment->user_id,
                            'comment' => $comment->comment,
                        ]);
                    }
                }

                // History
                if ($repeatingTask->task->history()->exists()) {
                    foreach ($repeatingTask->task->history as $history) {
                        $newRecurringTask->history()->create([
                            'task_id' => $newRecurringTask->id,
                            'user_id' => $history->user_id,
                            'action' => $history->action,
                        ]);
                    }
                }

                // Collaborators
                if ($repeatingTask->task->collaborators()->exists()) {
                    foreach ($repeatingTask->task->collaborators as $collaborator) {
                        $newRecurringTask->collaborators()->create([
                            'task_id' => $newRecurringTask->id,
                            'user_id' => $collaborator->user_id,
                        ]);
                    }
                }

                // Handle specific task types
                $this->createTaskTypeEntry($repeatingTask->task, $newRecurringTask->id);

                // Update the completed repeats counter
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

            $pendingTasks = Task::where('assigned_to', $user->id)->whereIn('status', ['Todo', 'In Progress', 'Review'])->get();
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

    private function createTaskTypeEntry($task, $newTaskID)
    {
        // Get the related data based on the task type
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
        // Now, create the related task type entry for the new task
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

                // If there are starting keywords, copy them to the new task
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
