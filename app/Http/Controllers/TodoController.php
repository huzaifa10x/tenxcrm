<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'task' => 'required|string|max:255',
        ]);

        Todo::create([
            'user_id' => auth()->id(),
            'date' => $request->date,
            'task' => $request->task,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Todo added successfully!']);
    }
   
    public function getTodosByDate(Request $request)
    {
        $todos = Todo::where('user_id', auth()->id())
            ->whereDate('date', $request->date)
            ->get(['id', 'task', 'status']);

        return response()->json($todos);
    }

    public function markAsDone(Request $request)
    {
        $todo = Todo::where('id', $request->id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $todo->update(['status' => 'completed']);

        return response()->json(['message' => 'Todo marked as completed!']);
    }
}
