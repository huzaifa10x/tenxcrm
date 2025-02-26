<?php

namespace App\Http\Controllers;

use App\Models\OffsiteTask;
use App\Models\ProjectKeyword;
use App\Models\Task;
use Illuminate\Http\Request;

class OffsiteTaskController extends Controller
{
    public function getStartingKwUrls($task_id)
    {
        $offsiteTask = ProjectKeyword::where('task_id', $task_id)->where('type', 'starting')->get();
        if ($offsiteTask->isEmpty()) {
            return response()->json([]);
        }
        return response()->json($offsiteTask);
    }

    public function getClosingKwUrls($task_id)
    {
        $offsiteTask = ProjectKeyword::where('task_id', $task_id)->where('type', 'closing')->get();
        if ($offsiteTask->isEmpty()) {
            return response()->json([]);
        }
        return response()->json($offsiteTask);
    }

    public function deleteStartingKwUrl($id)
    {
        $keyword = ProjectKeyword::find($id);

        if (!$keyword) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        $keyword->delete();

        return response()->json(['message' => 'Keyword deleted successfully']);
    }
}
