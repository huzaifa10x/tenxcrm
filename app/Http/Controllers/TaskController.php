<?php

namespace App\Http\Controllers;

use App\Events\TaskCompleted;
use App\Jobs\CreateDelayedTask;
use App\Jobs\CreateRecurringTaskJob;
use App\Models\AdminTask;
use App\Models\AmTask;
use App\Models\ContentTask;
use App\Models\CustomPeriod;
use App\Models\DesignTask;
use App\Models\DevelopmentTask;
use App\Models\OffsiteTask;
use App\Models\OnsiteTask;
use App\Models\Task;
use App\Models\Project;
use App\Models\ProjectKeyword;
use App\Models\RepeatingTask;
use App\Models\User;
use App\Models\Role;
use App\Models\SalesTask;
use App\Models\SeoAdminTask;
use App\Models\TaskAttachment;
use App\Models\TaskCompletion;
use App\Models\TaskHistory;
use App\Notifications\CustomNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Notifications\TaskAssigned;
use App\Notifications\TaskCreatedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $projects = Project::where('created_by', $user->id)
            ->orWhereHas('salesManagers', fn($q) => $q->where('user_id', $user->id))
            ->orWhereHas('accountManagers', fn($q) => $q->where('user_id', $user->id))
            ->orWhereHas('collaborators', fn($q) => $q->where('user_id', $user->id))
            ->orWhereHas('techManagers', fn($q) => $q->where('user_id', $user->id))
            ->orWhereHas('seoAdmins', fn($q) => $q->where('user_id', $user->id))->get();
        $users = User::all();
        $departments = Role::all();

        // Determine task query based on the 'type' parameter
        $taskQuery = Task::with(['project', 'createdBy', 'assignedTo'])->visibleTasks();

        // Handle different task types based on the 'type' query parameter
        if ($request->has('type')) {
            $type = $request->input('type');

            switch ($type) {
                case 'task-created-by-me':
                    // Tasks created by the user
                    $taskQuery->where('created_by', $user->id);
                    break;

                case 'where-im-collaborator':
                    // Tasks where the user is a collaborator
                    $taskQuery->whereHas('collaborators', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    });
                    break;

                case 'assigned-to-me':
                    // Tasks assigned to the user
                    $taskQuery->where('assigned_to', $user->id);
                    break;

                case 'all-tasks':
                    // Tasks accessible to admins or users with 'can view all tasks' permission
                    if (!$user->hasPermission('can view all tasks')) {
                        abort(403, 'You do not have permission to view all tasks.');
                    }
                    break;

                default:
                    $taskQuery->where('assigned_to', $user->id);
                    break;
            }
        } else {
            // If no 'type' is provided, you can default to showing tasks assigned to the user
            $taskQuery->where('assigned_to', $user->id);
        }

        // Apply additional filters based on other query parameters
        if ($request->has('start_date')) {
            $taskQuery->whereDate('created_at', '>=', $request->input('start_date'));
        }
        if ($request->has('end_date')) {
            $taskQuery->whereDate('created_at', '<=', $request->input('end_date'));
        }
        if ($request->has('user_id')) {
            $taskQuery->where('assigned_to', $request->input('user_id'));
        }
        if ($request->has('assigned_from')) {
            $taskQuery->where('created_by', $request->input('assigned_from'));
        }
        if ($request->has('project_id')) {
            $taskQuery->where('project_id', $request->input('project_id'));
        }

        // Fetch tasks
        $tasks = $taskQuery->get();
        // $tasks = $taskQuery->paginate(10);
        // $tasks->appends($request->query());

        return view('tasks.board', compact('tasks', 'projects', 'users', 'departments'));
    }
    public function search(Request $request)
    {
        $user = auth()->user();

        $tasks = Task::with('project:id,title') // Project title fetch karne ke liye
            ->where('title', 'LIKE', '%' . $request->query('query') . '%')
            ->where(function ($query) use ($user) {
                $query->where('created_by', $user->id)
                    ->orWhere('assigned_to', $user->id)
                    ->orWhereExists(function ($subQuery) use ($user) {
                        $subQuery->select(DB::raw(1))
                            ->from('task_collaborators')
                            ->whereRaw('task_collaborators.task_id = tasks.id')
                            ->where('task_collaborators.user_id', $user->id);
                    });

                if ($user->hasRole('ADMIN')) {
                    $query->orWhereRaw('1=1');
                }
            })
            ->select('id', 'title', 'project_id')
            ->limit(10)
            ->get();

        return response()->json($tasks->map(function ($task) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'project_title' => optional($task->project)->title ?? 'N/A',
            ];
        }));
    }
    public function create()
    {
        $projects = Project::all();
        $users = User::all();
        $departments = Role::all();
        return redirect()->route('tasks.index');
    }
    public function store(Request $request)
    {
        try {
            $task = Task::create([
                'title' => $request->title,
                'description' => $request->description,
                'project_id' => $request->project_id,
                'department_id' => $request->department_id,
                'created_by' => $request->created_by,
                'assigned_to' => $request->assigned_to,
                'priority' => $request->priority,
                'type' => $request->type,
                'status' => $request->status,
                'due_date' => $request->due_date,
                'start_date' => $request->start_date,
                'repeat_task' => $request->repeat_task ?? false,
            ]);

            switch ($request->type) {
                case 'CONTENT':
                    ContentTask::create([
                        'task_id' => $task->id,
                        'sub_type' => $request->sub_type,
                        'number_of_pages' => $request->number_of_pages,
                        'no_of_words' => $request->no_of_words,
                        'closing_input' => $request->closing_input,
                    ]);
                    break;

                case 'ONSITE':
                    OnsiteTask::create([
                        'task_id' => $task->id,
                        'sub_type' => $request->sub_type,
                        'closing_input' => $request->closing_input,
                    ]);
                    break;

                case 'OFFSITE':
                    $offsiteTask = OffsiteTask::create([
                        'task_id' => $task->id,
                        'sub_type' => $request->sub_type,
                    ]);

                    foreach ($request->keywords as $data) {
                        if (!empty($data['keyword']) && !empty($data['url'])) {
                            ProjectKeyword::create([
                                'project_id' => $task->project->id,
                                'task_id' => $task->id,
                                'keyword' => $data['keyword'],
                                'url' => $data['url'],
                                'type' => $request->oflinktype,
                            ]);
                        }
                    }

                    break;

                case 'DESIGN':
                    DesignTask::create([
                        'task_id' => $task->id,
                        'sub_type' => $request->sub_type,
                        'input_number' => $request->input_number,
                        'closing_input' => $request->closing_input,
                    ]);
                    break;

                case 'DEVELOPMENT':
                    DevelopmentTask::create([
                        'task_id' => $task->id,
                        'sub_type' => $request->sub_type,
                        'closing_input' => $request->closing_input,
                    ]);
                    break;

                case 'AM':
                    AmTask::create([
                        'task_id' => $task->id,
                        'sub_type' => $request->sub_type,
                    ]);
                    break;

                case 'SEO ADMIN':
                    SeoAdminTask::create([
                        'task_id' => $task->id,
                        'sub_type' => 'ADHOC',
                    ]);
                    break;

                case 'ADMIN':
                    AdminTask::create([
                        'task_id' => $task->id,
                        'sub_type' => 'ADHOC',
                    ]);
                    break;

                case 'SALES':
                    SalesTask::create([
                        'task_id' => $task->id,
                        'sub_type' => 'ADHOC',
                    ]);
                    break;
            }

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('task_attachments', 'public');

                    TaskAttachment::create([
                        'task_id' => $task->id,
                        'user_id' => auth()->id(),
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                    ]);
                }
            }

            if ($request->has('repeat_task') && $request->repeat_task) {
                $repeatEvery = $request->repeat_every;
                $count = $request->count;
                $startFrom = $request->start_from;
                $endDate = $request->end_date;

                RepeatingTask::create([
                    'task_id' => $task->id,
                    'repeat_every' => $repeatEvery,
                    'count' => $count,
                    'start_from' => $startFrom,
                    'end_date' => $endDate,
                    'completed_repeats' => 0,
                ]);
            }

            TaskHistory::create([
                'task_id' => $task->id,
                'user_id' => auth()->user()->id,
                'action' => "Task Created By " . auth()->user()->name,
            ]);

            $user = User::find($request->assigned_to);

            DB::table('notifications')->insert([
                'id' => Str::uuid(),
                'type' => \App\Notifications\CustomNotification::class,
                'notifiable_type' => \App\Models\User::class,
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'title' => "New task added by " . auth()->user()->name,
                    'message' => "A new task '" . $request->title . "' has been assigned.",
                    'url' => url("tasks/{$task->id}")
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Task created successfully!');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function show(Request $request, Task $task)
    {
        $users = User::all();
        $projects = Project::all();
        $departments = Role::all();

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
            case 'ADHOC':
                $relatedData = null;
                break;
            default:
                $relatedData = null;
                break;
        }

        if (!$request->is_ajax) {
            session()->flash('is_not_ajax', true);
        }

        return view('tasks.show', compact('task', 'projects', 'users', 'departments', 'relatedData'));
    }
    public function showTask($id)
    {
        session()->flash('open_task_id', $id);
        return redirect()->route('tasks.index');
    }
    public function destroy(Task $task)
    {
        $user = User::find($task->assigned_to);
        $url = route('tasks.index');
        $user->notify(new CustomNotification("Task deleted by " . auth()->user()->name, $task->title . " deleted", $url));
        $task->delete();
        return redirect()->back()->with('success', 'Task deleted successfully!');
    }
    public function addAttachment(Request $request, Task $task)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,webp',
        ]);

        $filePath = $request->file('file')->store('task_attachments', 'public');

        $attachment = TaskAttachment::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'file_path' => $filePath,
            'original_name' => $request->file('file')->getClientOriginalName(),
        ]);

        TaskHistory::create([
            'task_id' => $task->id,
            'user_id' => auth()->user()->id,
            'action' => "Task Attachment Added By " . auth()->user()->name,
        ]);

        return redirect()->route('tasks.show', ['task' => $task->id])->with('success', 'Task Attachment Added!');
    }
    public function removeAttachment(Task $task, TaskAttachment $attachment)
    {
        if (file_exists(storage_path('app/public/' . $attachment->file_path))) {
            unlink(storage_path('app/public/' . $attachment->file_path));
        }
        $attachment->delete();
        TaskHistory::create([
            'task_id' => $task->id,
            'user_id' => auth()->user()->id,
            'action' => "Task Attachment Deleted By " . auth()->user()->name,
        ]);

        return redirect()->route('tasks.show', ['task' => $task->id])->with('success', 'Attachment deleted successfully!');
    }
    public function updateStatus(Request $request)
    {
        $task = Task::findOrFail($request->task_id);

        TaskHistory::create([
            'task_id' => $task->id,
            'user_id' => auth()->user()->id,
            'action' => "Status changed from " . $task->status . " to " . $request->status . " by " . auth()->user()->name,
        ]);

        $task->status = $request->status;
        $task->save(); // Save before notification

        $user = User::find($task->assigned_to);

        if ($user) {
            DB::table('notifications')->insert([
                'id' => Str::uuid(),
                'type' => \App\Notifications\CustomNotification::class,
                'notifiable_type' => \App\Models\User::class,
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'title' => "Task status updated by " . auth()->user()->name,
                    'message' => "The status of '" . $task->title . "' has been updated.",
                    'url' => url("tasks/{$task->id}")
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['message' => 'Task status updated successfully']);
    }
    public function deleteAttachment(TaskAttachment $attachment)
    {
        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        TaskHistory::create([
            'task_id' => $attachment->task->id,
            'user_id' => auth()->user()->id,
            'action' => "Task Attachment Deleted By " . auth()->user()->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attachment deleted successfully!'
        ]);
    }
    public function tasksAttachmentsStore(Request $request, Task $task)
    {
        $request->validate([
            'attachments' => 'required|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx,webp',
        ]);

        $uploadedCount = 0;

        foreach ($request->file('attachments') as $file) {
            $path = $file->store('task_attachments', 'public');

            $task->attachments()->create([
                'file_path' => $path,
                'file_type' => $file->getClientMimeType(),
                'original_name' => $file->getClientOriginalName(),
                'user_id' => auth()->id(),
            ]);

            $uploadedCount++;
        }

        $attachments = $task->attachments()->with('user')->get();

        if ($uploadedCount > 0) {
            TaskHistory::create([
                'task_id' => $task->id,
                'user_id' => auth()->user()->id,
                'action' => "$uploadedCount Attachment(s) Added by " . auth()->user()->name,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Attachments uploaded successfully.',
            'attachments' => $attachments,
        ]);
    }
    public function jsonTaskAttachments(Task $task)
    {
        return response()->json([
            'attachments' => $task->attachments()->with('user')->get()
        ]);
    }
    public function updateTaskAjax(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->{$request->field} = $request->value;
        $task->save();

        TaskHistory::create([
            'task_id' => $task->id,
            'user_id' => auth()->user()->id,
            'action' => "Task " . $request->field . " updated By " . auth()->user()->name,
        ]);

        $user = User::find($task->assigned_to);
        if ($user) {
            DB::table('notifications')->insert([
                'id' => Str::uuid(),
                'type' => \App\Notifications\CustomNotification::class,
                'notifiable_type' => \App\Models\User::class,
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'title' => "Task updated by " . auth()->user()->name,
                    'message' => $task->title . " has been updated.",
                    'url' => url("tasks/{$task->id}")
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            "message" => ucfirst($request->field) . " updated successfully!"
        ]);
    }
    public function completeTask(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'fields' => 'required|array',
            'fields.*.name' => 'required|string',
            'fields.*.value' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,webp',
        ]);

        $filePath = null;

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('task_attachments', 'public');
        }

        foreach ($request->fields as $index => $field) {
            TaskCompletion::create([
                'task_id' => $request->task_id,
                'field_name' => $field['name'],
                'field_value' => ($index === 0 && $filePath)
                    ? $filePath
                    : $field['value'],
            ]);
        }

        return response()->json([
            'message' => 'Task fields saved successfully',
            'file_path' => $filePath,
        ], 200);
    }
    public function markAsCompleted($id)
    {
        $task = Task::findOrFail($id);
        $project = $task->project;
        $task->update(['status' => 'Completed', 'completed_date' => now()]);

        $accountManager = $project->accountManagers[0]->id ?? 1;
        $seoAdmin = $project->seoAdmins[0]->id ?? 1;
        $techManger = $project->techManagers[0]->id ?? 1;
        $contentWriter = $project->contentWriters[0]->id ?? 1;

        $dubaiTime = Carbon::now()->timezone('Asia/Dubai');
        $keywordsApprovalTasks = [
            ["type" => "SEO ADMIN", "title" => 'Create the Project on SE Ranking', "assignee" => $techManger],
            ["type" => "AM", "title" => 'Send SEO Benchmark Report to client', "assignee" => $accountManager],
            ["type" => "ONSITE", "title" => 'First On-site', "assignee" => $techManger],
            ["type" => "SEO ADMIN", "title" => 'Add KW Mapping', "assignee" => $seoAdmin],
            ["type" => "SEO ADMIN", "title" => 'On-site Audit (from SE ranking)', "assignee" => $seoAdmin],
            ["type" => "SEO ADMIN", "title" => 'Content Audit', "assignee" => $seoAdmin],
            ["type" => "SEO ADMIN", "title" => 'Backlink Audit & DISAVOW', "assignee" => $seoAdmin],
        ];

        if ($task->type == "CONTENT") {
            $newContentApprovalTask = Task::create([
                'is_automated' => 1,
                'title' => "Client Content Approval Request",
                'description' => 'Please review and obtain client approval for the content related to task ' . url("tasks/{$task->id}") . '.',
                'project_id' => $project->id,
                'department_id' => null,
                'created_by' => 1,
                'assigned_to' => $accountManager,
                'priority' => 'High',
                'type' => "AM",
                'status' => 'Todo',
                'due_date' => Carbon::now()->addDays(7),
                'start_date' => $dubaiTime,
                'repeat_task' => false,
            ]);
            ContentTask::create([
                "task_id" => $newContentApprovalTask->id,
                "sub_type" => $task->contentTask->sub_type,
                "number_of_pages" => $task->contentTask->number_of_pages,
            ]);
            foreach ($task->attachments as $attachment) {
                $newContentApprovalTask->attachments()->create([
                    'file_path' => $attachment->file_path,
                    'file_type' => $attachment->getClientMimeType(),
                    'original_name' => $attachment->getClientOriginalName(),
                    'user_id' => auth()->id(),
                ]);
            }
            foreach ($task->comments as $comment) {
                $newContentApprovalTask->comments()->create([
                    'task_id' => $newContentApprovalTask->id,
                    'user_id' => $comment->user_id,
                    'comment' => $comment->comment,
                ]);
            }
            foreach ($task->history as $history) {
                $newContentApprovalTask->history()->create([
                    'task_id' => $newContentApprovalTask->id,
                    'user_id' => $history->user_id,
                    'action' => $history->action,
                ]);
            }
            foreach ($task->collaborators as $collaborator) {
                $newContentApprovalTask->collaborators()->create([
                    'task_id' => $newContentApprovalTask->id,
                    'user_id' => $collaborator->user_id,
                ]);
            }
            $newContentApprovalTask->collaborators()->create([
                'task_id' => $newContentApprovalTask->id,
                'user_id' => $task->assigned_to,
            ]);
        }

        switch ($task->title) {
            case 'First Client Meeting':
                Task::insert([
                    [
                        'is_automated' => 1,
                        'created_by' => 1,
                        'project_id' => $project->id,
                        'title' => 'Keywords Approval',
                        'assigned_to' => $accountManager,
                        'status' => 'Todo',
                        'start_date' => $dubaiTime,
                        'type' => 'AM',
                        'description' => 'system generated task'
                    ],
                ]);
                break;

            case 'Keywords Approval':
                foreach ($keywordsApprovalTasks as $loopTask) {
                    Task::create([
                        'is_automated' => 1,
                        'title' => $loopTask['title'],
                        'description' => "system generated task",
                        'project_id' => $project->id,
                        'department_id' => null,
                        'created_by' => 1,
                        'assigned_to' => $loopTask['assignee'],
                        'priority' => 'High',
                        'type' => $loopTask['type'],
                        'status' => 'Todo',
                        'due_date' => Carbon::now()->addDays(7),
                        'start_date' => $dubaiTime,
                        'repeat_task' => false,
                    ]);
                }
                break;
            case 'Content Audit':
                Task::create([
                    'is_automated' => 1,
                    'created_by' => 1,
                    'project_id' => $project->id,
                    'title' => 'SEO Content Request',
                    'description' => 'SEO Content Request ( CONTENT AUDIT+ KW MAPPING +CONTENT INTAKE FORM)',
                    'assigned_to' => $contentWriter,
                    'status' => 'Todo',
                    'start_date' => $dubaiTime,
                    'type' => 'CONTENT',
                ]);
                break;

            case 'SEO Content Request':
                Task::create([
                    'is_automated' => 1,
                    'created_by' => 1,
                    'project_id' => $project->id,
                    'title' => 'PUBLISH CONTENT',
                    'assigned_to' => $accountManager,
                    'status' => 'Todo',
                    'start_date' => $dubaiTime,
                    'type' => 'AM',
                    'description' => 'system generated task'
                ]);
                break;

            case 'First On-site':
                Task::create([
                    'is_automated' => 1,
                    'created_by' => 1,
                    'project_id' => $project->id,
                    'title' => 'Schedule R1',
                    'assigned_to' => $seoAdmin,
                    'status' => 'Todo',
                    'start_date' => $dubaiTime,
                    'type' => 'SEO ADMIN',
                    'description' => 'system generated task'
                ]);

                Task::create([
                    'is_automated' => 1,
                    'created_by' => 1,
                    'project_id' => $project->id,
                    'title' => 'Schedule R2',
                    'assigned_to' => $seoAdmin,
                    'status' => 'Todo',
                    'start_date' => now()->addDays(30),
                    'type' => 'ADHOC',
                    'description' => 'system generated task'
                ]);

                Task::create([
                    'is_automated' => 1,
                    'created_by' => 1,
                    'project_id' => $project->id,
                    'title' => 'Schedule R3',
                    'assigned_to' => $seoAdmin,
                    'status' => 'Todo',
                    'start_date' => now()->addDays(60),
                    'type' => 'ADHOC',
                    'description' => 'system generated task'
                ]);

                break;

            case 'Create the Project on SE Ranking':
                Task::create([
                    'is_automated' => 1,
                    'created_by' => 1,
                    'project_id' => $project->id,
                    'title' => 'Request Local Listing',
                    'assigned_to' => $seoAdmin,
                    'status' => 'Todo',
                    'start_date' => $dubaiTime,
                    'type' => 'SEO ADMIN',
                    'description' => 'system generated task'
                ]);
                break;

            case 'Intro Call':
                Task::create([
                    'is_automated' => 1,
                    'created_by' => 1,
                    'project_id' => $project->id,
                    'title' => 'Request Backlinks',
                    'assigned_to' => $seoAdmin,
                    'status' => 'Todo',
                    'start_date' => now()->addDays(30),
                    'type' => 'SEO ADMIN',
                    'description' => 'system generated task'
                ]);
                break;

            default:
                break;
        }

        TaskHistory::create([
            'task_id' => $id,
            'user_id' => auth()->user()->id,
            'action' => auth()->user()->name . " marked this task completed",
        ]);

        DB::table('notifications')->insert([
            'id' => Str::uuid(),
            'type' => \App\Notifications\CustomNotification::class,
            'notifiable_type' => \App\Models\User::class,
            'notifiable_id' => $task->createdBy->id,
            'data' => json_encode([
                'title' => "Task completed by " . auth()->user()->name,
                'message' => $task->title . " has been completed.",
                'url' => url("tasks/{$task->id}")
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Task marked as completed.']);
    }
}
