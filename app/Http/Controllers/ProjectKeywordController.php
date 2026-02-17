<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectKeyword;
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
            'task_id' => 'required|exists:tasks,id',
            'type' => 'required|string',
        ]);

        foreach ($validated['keywords'] as $item) {
            ProjectKeyword::create([
                'project_id'       => $project->id,
                'task_id'          => $validated['task_id'],
                'type'             => $validated['type'],
                'keyword'          => $item['keyword'],
                'url'              => $item['url'],
                'destination_url'  => $item['destination_url'] ?? null,
                'link_cost'        => $item['link_cost'] ?? 0,
                'created_by'       => auth()->id(),
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
