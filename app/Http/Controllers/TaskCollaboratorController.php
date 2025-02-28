<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskCollaborator;
use App\Models\TaskHistory;
use App\Models\User;

class TaskCollaboratorController extends Controller
{
    public function index($task_id)
    {
        $collaborators = TaskCollaborator::with(['user', 'task'])
            ->where('task_id', $task_id)
            ->get();

        return response()->json($collaborators);
    }

    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'user_id' => 'required|exists:users,id'
        ]);

        // Check if the user is already assigned to the task
        if (TaskCollaborator::where('task_id', $request->task_id)
            ->where('user_id', $request->user_id)
            ->exists()
        ) {
            return response()->json(['message' => 'User is already a collaborator'], 400);
        }

        $collaborator = TaskCollaborator::create($request->all());

        $user =  User::where('id', $request->user_id)->first();

        TaskHistory::create([
            'task_id' => $collaborator->task->id,
            'user_id' => auth()->user()->id,
            'action' => $user->name . " was added as a collaborator by " . auth()->user()->name,
        ]);

        return response()->json([
            'message' => 'Collaborator added successfully',
            'data' => $collaborator
        ]);
    }

    public function destroy($id)
    {
        $collaborator = TaskCollaborator::findOrFail($id);
        $collaborator->delete();

        TaskHistory::create([
            'task_id' => $collaborator->task->id,
            'user_id' => auth()->user()->id,
            'action' => "Task Collaborator " . $collaborator->user->name . " Removed By " . auth()->user()->name,
        ]);

        return response()->json(['message' => 'Collaborator removed successfully']);
    }
}
