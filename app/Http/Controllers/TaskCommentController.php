<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\TaskComment;
use App\Models\User;
use App\Notifications\CustomNotification;

class TaskCommentController extends Controller
{
    public function index($task_id)
    {
        $comments = TaskComment::where('task_id', $task_id)->with('user')->latest()->get();
        return response()->json($comments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'comment' => 'required|string'
        ]);

        
        $comment = TaskComment::create([
            'task_id' => $request->task_id,
            'user_id' => auth()->id(),
            'comment' => $request->comment
        ]);
        
        return response()->json(['success' => true, 'comment' => $comment]);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['comment' => 'required|string']);

        $comment = TaskComment::findOrFail($id);
        if ($comment->user_id != auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->update(['comment' => $request->comment]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $comment = TaskComment::findOrFail($id);
        if ($comment->user_id != auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['success' => true]);
    }
}
