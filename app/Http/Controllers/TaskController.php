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

class TaskController extends Controller
{
    public function index()
    {
        return redirect()->route('tasks.board.index');
    }

    public function boardView()
    {
        $user = auth()->user();
        $projects = Project::all();
        $users = User::all();
        $departments = Role::all();

        if ($user->hasPermission('can view all tasks')) {
            $tasks = Task::with(['project', 'createdBy', 'assignedTo'])->visibleTasks()->get();
        } elseif ($user->hasPermission('can view own tasks')) {
            $tasks = Task::with(['project', 'createdBy', 'assignedTo'])
                ->where('assigned_to', $user->id)
                ->visibleTasks()->get();
        } else {
            $tasks = collect();
        }

        return view('tasks.board', compact('tasks', 'projects', 'users', 'departments'));
    }

    public function assignedTasks()
    {
        $user = auth()->user();
        $projects = Project::all();
        $users = User::all();
        $departments = Role::all();
        if (($user->hasPermission('can create tasks'))) {
            $tasks = Task::with(['project', 'createdBy', 'assignedTo'])
                ->where('created_by', $user->id)
                ->visibleTasks()->get();
        } else {
            $tasks = Task::with(['project', 'createdBy', 'assignedTo'])
                ->where('assigned_to', $user->id)
                ->visibleTasks()->get();
        }

        return view('tasks.board', compact('tasks', 'projects', 'users', 'departments'));
    }

    public function formeTasks()
    {
        $user = auth()->user();
        $projects = Project::all();
        $users = User::all();
        $departments = Role::all();

        $tasks = Task::with(['project', 'createdBy', 'assignedTo'])
            ->where('assigned_to', $user->id)->visibleTasks()->get();

        return view('tasks.board', compact('tasks', 'projects', 'users', 'departments'));
    }

    public function collaboratedTasks()
    {
        $user = auth()->user();
        $projects = Project::all();
        $users = User::all();
        $departments = Role::all();
        $tasks = Task::with(['project', 'createdBy', 'assignedTo'])
            ->whereHas('collaborators', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->visibleTasks()
            ->get();

        return view('tasks.board', compact('tasks', 'projects', 'users', 'departments'));
    }


    public function create()
    {
        $projects = Project::all();
        $users = User::all();
        $departments = Role::all();
        return redirect()->route('tasks.board.index');
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

            $kwUrls = is_string($request->kw_url) ? json_decode($request->kw_url, true) : $request->kw_url;

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
                        'file_path' => $path,
                    ]);
                }
            }

            if ($request->has('repeat_task') && $request->repeat_task) {
                $repeatEvery = (int)$request->repeat_every;
                $repeat_count = (int)$request->repeat_count;
                $nextRunAt = Carbon::now()->addHours($repeatEvery);

                RepeatingTask::create([
                    'task_id' => $task->id,
                    'repeat_every' => $repeatEvery,
                    'next_run_at' => $nextRunAt,
                    'repeat_count' => $repeat_count ? $repeat_count : null,
                ]);
            }

            TaskHistory::create([
                'task_id' => $task->id,
                'user_id' => auth()->user()->id,
                'action' => "Task Created By " . auth()->user()->name,
            ]);

            $user = User::find($request->assigned_to);
            $url = url("tasks/{$task->id}");
            $user->notify(new CustomNotification(
                "New task added by " . auth()->user()->name,
                "A new task '" . $request->title . "' has been assigned.",
                $url
            ));

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

    // Delete task
    public function showTask($id)
    {
        session()->flash('open_task_id', $id);
        return redirect()->route('tasks.board.index');
    }

    public function destroy(Task $task)
    {
        $user = User::find($task->assigned_to);
        $url = route('tasks.board.index');
        $user->notify(new CustomNotification("Task deleted by " . auth()->user()->name, $task->title . " deleted", $url));
        $task->delete();
        return redirect()->back()->with('success', 'Task deleted successfully!');
    }

    public function addAttachment(Request $request, Task $task)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,png,pdf,docx,txt',
        ]);

        // Store the file in the 'task_attachments' directory
        $filePath = $request->file('file')->store('task_attachments', 'public');

        // Create the attachment entry in the database
        $attachment = TaskAttachment::create([
            'task_id' => $task->id,
            'file_path' => $filePath,
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
        // Delete the file from storage
        if (file_exists(storage_path('app/public/' . $attachment->file_path))) {
            unlink(storage_path('app/public/' . $attachment->file_path));
        }

        // Delete the attachment record from the database
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
            $url = url("tasks/{$task->id}");
            $user->notify(new CustomNotification(
                "Task status updated by " . auth()->user()->name,
                "The status of '" . $task->title . "' has been updated.",
                $url
            ));
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
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);
        $uploadedCount = 0;
        foreach ($request->file('attachments') as $file) {
            $path = $file->store('task_attachments', 'public');

            $task->attachments()->create([
                'file_path' => $path,
                'file_type' => $file->getClientMimeType(),
            ]);
            $uploadedCount++;
        }

        $attachments = $task->attachments()->get();

        if ($uploadedCount > 0) {
            TaskHistory::create([
                'task_id' => $task->id,
                'user_id' => auth()->user()->id,
                'action' => "$uploadedCount Attachment(s) Added by " . auth()->user()->name,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Attachments uploaded successfully.', 'attachments' => $attachments]);
    }
    public function jsonTaskAttachments(Task $task)
    {
        return response()->json([
            'attachments' => $task->attachments()->get()
        ]);
    }
    public function updateTaskAjax(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        TaskHistory::create([
            'task_id' => $task->id,
            'user_id' => auth()->user()->id,
            'action' => "Task " . $request->field . " updated from " . $task->{$request->field} . " to " . $request->value . " By " . auth()->user()->name,
        ]);
        $task->{$request->field} = $request->value;
        $task->save();

        return response()->json([
            "message" => ucfirst($request->field) . " updated successfully!"
        ]);
    }
    public function disableRepeating(Task $task)
    {
        $task->update(['repeat_task' => 0]);

        TaskHistory::create([
            'task_id' => $task->id,
            'user_id' => auth()->user()->id,
            'action' => auth()->user()->name . " has been stopped repeating of this task",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task repeating has been disabled.'
        ]);
    }

    public function enableRepeating(Task $task)
    {
        $task->update(['repeat_task' => 1]);

        TaskHistory::create([
            'task_id' => $task->id,
            'user_id' => auth()->user()->id,
            'action' => auth()->user()->name . " has been start repeating of this task",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task repeating has been enabled.'
        ]);
    }

    public function completeTask(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'fields' => 'required|array',
            'fields.*.name' => 'required|string',
            'fields.*.value' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
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
        $accountManager = $project->accountManagers[0]->id;
        $seoTech = $project->techManagers[0]->id;
        $seoAdmin = $project->seoAdmins[0]->id;
        $contentWriter = $project->contentWriters[0]->id;

        if (
            !$accountManager ||
            !$seoTech ||
            !$seoAdmin ||
            !$contentWriter
        ) {
            return "Please assign all the roles to the project";
        }

        $dubaiTime = Carbon::now()->timezone('Asia/Dubai');

        $keywordsApprovalTasks = [
            ["title" => 'Create the Project on SE Ranking', "assignee" => $seoTech],
            ["title" => 'Send SEO Benchmark Report to client', "assignee" => $accountManager],
            ["title" => 'First On-site', "assignee" => $seoTech],
            ["title" => 'Add KW Mapping', "assignee" => $seoAdmin],
            ["title" => 'On-site Audit (from SE ranking', "assignee" => $seoAdmin],
            ["title" => 'Content Audit', "assignee" => $seoAdmin],
            ["title" => 'Backlink Audit & DISAVOW', "assignee" => $seoAdmin],
        ];

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
                        'type' => 'ADHOC',
                        'description' => 'system generated task'
                    ],
                ]);
                break;

            case 'Keywords Approval':
                foreach ($keywordsApprovalTasks as $task) {
                    Task::create([
                        'is_automated' => 1,
                        'title' => $task['title'],
                        'description' => "system generated task",
                        'project_id' => $project->id,
                        'department_id' => null,
                        'created_by' => 1,
                        'assigned_to' => $task['assignee'],
                        'priority' => 'High',
                        'type' => 'ADHOC',
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
                    'type' => 'ADHOC',
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
                    'type' => 'ADHOC',
                    'description' => 'system generated task'
                ]);
                break;

            case 'First On-site':
                $task = Task::create([
                    'is_automated' => 1,
                    'created_by' => 1,
                    'project_id' => $project->id,
                    'title' => 'Schedule R1',
                    'assigned_to' => $seoAdmin,
                    'status' => 'Todo',
                    'start_date' => $dubaiTime,
                    'type' => 'ADHOC',
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
                    'type' => 'ADHOC',
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
                    'type' => 'ADHOC',
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

        return response()->json(['success' => true, 'message' => 'Task marked as completed.']);
    }
}
