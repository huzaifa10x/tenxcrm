<?php

namespace App\Http\Controllers;

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
use App\Models\RepeatingTask;
use App\Models\User;
use App\Models\Role;
use App\Models\SalesTask;
use App\Models\SeoAdminTask;
use App\Models\StartingKwUrl;
use App\Models\TaskAttachment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasPermission('can view all tasks')) {
            $tasks = Task::with(['project', 'createdBy', 'assignedTo'])->get();
        } elseif ($user->hasPermission('can view own tasks')) {
            $tasks = Task::with(['project', 'createdBy', 'assignedTo'])
                ->where('assigned_to', $user->id)
                ->get();
        } else {
            $tasks = collect();
        }

        return view('tasks.board', compact('tasks'));
    }

    public function boardView()
    {
        $user = auth()->user();
        $projects = Project::all();
        $users = User::all();
        $departments = Role::all();

        if ($user->hasPermission('can view all tasks')) {
            $tasks = Task::with(['project', 'createdBy', 'assignedTo'])->get();
        } elseif ($user->hasPermission('can view own tasks')) {
            $tasks = Task::with(['project', 'createdBy', 'assignedTo'])
                ->where('assigned_to', $user->id)
                ->get();
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
                ->get();
        } else {
            $tasks = Task::with(['project', 'createdBy', 'assignedTo'])
                ->where('assigned_to', $user->id)
                ->get();
        }

        return view('tasks.board', compact('tasks', 'projects', 'users', 'departments'));
    }

    public function formeTasks()
    {
        $user = auth()->user();
        $projects = Project::all();
        $users = User::all();
        $departments = Role::all();

        if (($user->hasPermission('can view own tasks'))) {
            $tasks = Task::with(['project', 'createdBy', 'assignedTo'])
                ->where('assigned_to', $user->id)
                ->get();
        }

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

                    if (!empty($kwUrls) && is_array($kwUrls)) {
                        foreach ($kwUrls as $kw) {
                            if (isset($kw['keyword']) && isset($kw['url'])) {
                                $domain = parse_url($kw['url'], PHP_URL_HOST); // Extract domain

                                StartingKwUrl::create([
                                    'keyword' => $kw['keyword'],
                                    'url' => $kw['url'],
                                    'domain' => $domain,
                                    'task_id' => $task->id,
                                    'project_id' => $request->project_id,
                                    'offsite_task_id' => $offsiteTask->id,
                                ]);
                            }
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
                $nextRunAt = Carbon::now()->addHours($repeatEvery);

                RepeatingTask::create([
                    'task_id' => $task->id,
                    'repeat_every' => $repeatEvery,
                    'next_run_at' => $nextRunAt,
                ]);
            }

            return redirect()->route('tasks.board.index')->with('success', 'Task created successfully!');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function show(Request $request, Task $task)
    {
        if (!$request->is_ajax) {
            return redirect()->route('tasks.board.index');
        }
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
                $relatedData = OffsiteTask::with('startingKwUrls')->where('task_id', $task->id)->first();
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

        return view('tasks.show', compact('task', 'projects', 'users', 'departments', 'relatedData'));
    }

    // Delete task
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.board.index')->with('success', 'Task deleted successfully!');
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

        // Return the attachment data
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

        return redirect()->route('tasks.show', ['task' => $task->id])->with('success', 'Attachment deleted successfully!');
    }
    public function updateStatus(Request $request)
    {
        $task = Task::findOrFail($request->task_id);
        $task->status = $request->status;
        $task->save();

        return response()->json(['message' => 'Task status updated successfully']);
    }
    public function deleteAttachment(TaskAttachment $attachment)
    {
        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();
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

        foreach ($request->file('attachments') as $file) {
            $path = $file->store('task_attachments', 'public');

            $task->attachments()->create([
                'file_path' => $path,
                'file_type' => $file->getClientMimeType(),
            ]);
        }

        $attachments = $task->attachments()->get();

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
        $task->{$request->field} = $request->value;
        $task->save();

        return response()->json([
            "message" => ucfirst($request->field) . " updated successfully!"
        ]);
    }
    public function disableRepeating(Task $task)
    {
        $task->update(['repeat_task' => 0]);

        return response()->json([
            'success' => true,
            'message' => 'Task repeating has been disabled.'
        ]);
    }

    public function enableRepeating(Task $task)
    {
        $task->update(['repeat_task' => 1]);

        return response()->json([
            'success' => true,
            'message' => 'Task repeating has been enabled.'
        ]);
    }
}
