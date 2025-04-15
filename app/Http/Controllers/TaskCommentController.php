<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\TaskComment;
use App\Models\User;
use App\Notifications\CustomNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

        $user = User::find($comment->task->assigned_to);

        DB::table('notifications')->insert([
            'id' => Str::uuid(),
            'type' => \App\Notifications\CustomNotification::class,
            'notifiable_type' => \App\Models\User::class,
            'notifiable_id' => $user->id,
            'data' => json_encode([
                'title' => auth()->user()->name . " commented on " . $comment->task->title,
                'message' => auth()->user()->name . " has been commented on " . $comment->task->title,
                'url' => url("tasks/{$request->task_id}")
            ]),
            'created_at' => now(),
            'updated_at' => now(),
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
