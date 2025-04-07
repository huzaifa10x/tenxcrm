<?php

namespace App\Http\Controllers;

use App\Models\ProjectNote;
use Illuminate\Http\Request;

class ProjectNoteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'note' => 'required|string',
        ]);

        $note = ProjectNote::create([
            'project_id' => $request->project_id,
            'user_id' => auth()->id(),
            'note' => $request->note,
        ]);

        return response()->json(['status' => 'success', 'note' => $note->load('user')]);
    }

    public function destroy(ProjectNote $note)
    {
        if ($note->user_id != auth()->id()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $note->delete();

        return response()->json(['status' => 'deleted']);
    }

    public function fetch($projectId)
    {
        $notes = ProjectNote::with('user')->where('project_id', $projectId)->latest()->get();
        return response()->json($notes);
    }
}
