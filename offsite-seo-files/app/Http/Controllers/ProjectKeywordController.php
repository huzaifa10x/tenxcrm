<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectKeyword;
use App\Models\Task;
use Illuminate\Http\Request;

class ProjectKeywordController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'keywords' => 'required|array',
            'keywords.*.keyword' => 'required|string',
            'keywords.*.url' => 'required|url',
            'keywords.*.destination_url' => 'nullable',
            'keywords.*.link_cost' => 'nullable|numeric',
            'keywords.*.rel' => 'nullable|in:dofollow,nofollow',
            'keywords.*.dr' => 'nullable|integer|min:0|max:100',
            'task_id' => 'required|exists:tasks,id',
            'type' => 'required|string',
        ]);

        // Credit the link to the Offsite employee who logged it; if someone else
        // (admin, AM) logs it on their behalf, credit the task's assignee.
        $task = Task::find($validated['task_id']);
        $creditTo = auth()->user()->hasRole('OFFSITE') ? auth()->id() : ($task->assigned_to ?? auth()->id());
        $isClosing = $validated['type'] === 'closing';

        foreach ($validated['keywords'] as $item) {
            ProjectKeyword::create([
                'project_id'       => $project->id,
                'task_id'          => $validated['task_id'],
                'type'             => $validated['type'],
                'keyword'          => $item['keyword'],
                'url'              => $item['url'],
                'destination_url'  => $item['destination_url'] ?? null,
                'link_cost'        => $item['link_cost'] ?? 0,
                'created_by'       => $creditTo,
                'rel'              => $isClosing ? ($item['rel'] ?? null) : null,
                'dr'               => $isClosing ? ($item['dr'] ?? null) : null,
                'link_status'      => $isClosing ? 'live' : null,
            ]);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Keywords successfully saved',
            'data'    => $validated['keywords']
        ]);
    }

    public function delete($id)
    {
        $keyword = ProjectKeyword::find($id);

        if (!$keyword) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        $keyword->delete();

        return response()->json(['message' => 'Keyword deleted successfully']);
    }
}
