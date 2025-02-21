<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectKeyword;
use Illuminate\Http\Request;

class ProjectKeywordController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'keywords' => 'required|array',
            'keywords.*.keyword' => 'required|string',
            'keywords.*.url' => 'required|url',
            'task_id' => 'required|exists:tasks,id',
        ]);

        foreach ($request->keywords as $data) {
            ProjectKeyword::create([
                'project_id' => $project->id,
                'task_id' => $request->task_id,
                'keyword' => $data['keyword'],
                'url' => $data['url'],
            ]);
        }

        return response()->json(['message' => 'Keywords saved successfully']);
    }
}
