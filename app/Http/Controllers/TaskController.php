<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\Role;
use App\Models\TaskAttachment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\FuncCall;

class TaskController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasPermission('can view all tasks')) {
            $tasks = Task::with(['project', 'createdBy', 'assignedTo'])->paginate(10);
        } elseif ($user->hasPermission('can view own tasks')) {
            $tasks = Task::with(['project', 'createdBy', 'assignedTo'])
                ->where('assigned_to', $user->id)
                ->paginate(10);
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
            $tasks = Task::with(['project', 'createdBy', 'assignedTo'])->paginate(10);
        } elseif ($user->hasPermission('can view own tasks')) {
            $tasks = Task::with(['project', 'createdBy', 'assignedTo'])
                ->where('assigned_to', $user->id)
                ->paginate(10);
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
                ->paginate(10);
        } else {
            $tasks = Task::with(['project', 'createdBy', 'assignedTo'])
                ->where('assigned_to', $user->id)
                ->paginate(10);
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
                ->paginate(10);
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
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'created_by' => 'required|exists:users,id',
            'department_id' => 'required|exists:roles,id',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'type' => 'required|in:CONTENT,ONSITE,OFFSITE,DESIGN,DEVELOPMENT,ACCOUNT MANAGER,SEO ADMIN,ADMIN,SALES',
            'status' => 'required|in:Todo,In Progress,Review,Completed,Blocked',
            'due_date' => 'nullable|date',
            'start_date' => 'nullable|date_format:Y-m-d H:i:s',
            'completed_date' => 'nullable|date',
            'estimated_time' => 'nullable|integer',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpeg,png,pdf,docx,txt|max:2048',
        ]);

        // $request->merge([
        //     'start_date' => Carbon::now()->format('Y-m-d H:i:s'),
        // ]);

        $task = Task::create($request->except('attachments'));

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('task_attachments', 'public'); // Store file in storage

                // Create an attachment record in the database
                TaskAttachment::create([
                    'task_id' => $task->id,
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()->route('tasks.board.index')->with('success', 'Task created successfully!');
    }

    public function show(Request $request, Task $task)
    {
        if (!$request->is_ajax) {
            return redirect()->route('tasks.board.index');
        }
        $users = User::all();
        $projects = Project::all();
        $departments = Role::all();
        return view('tasks.show', compact('task', 'projects', 'users', 'departments'));
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
}
