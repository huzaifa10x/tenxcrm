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
            'type' => 'required',
        ]);

        foreach ($request->keywords as $data) {
            ProjectKeyword::create([
                'project_id' => $project->id,
                'task_id' => $request->task_id,
                'keyword' => $data['keyword'],
                'url' => $data['url'],
                'type' => $request->type,
            ]);
        }

        return response()->json(['message' => 'Keywords saved successfully', 'data' => ProjectKeyword::where('task_id', $request->task_id)->where('type', 'starting')->get()]);
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
