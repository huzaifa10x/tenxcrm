<?php

namespace App\Http\Controllers;

use App\Models\OffsiteTask;
use App\Models\StartingKwUrl;

class OffsiteTaskController extends Controller
{
    public function getStartingKwUrls($task_id)
    {
        $offsiteTask = OffsiteTask::where('task_id', $task_id)->first();

        if (!$offsiteTask) {
            return response()->json([]);
        }

        $startingKwUrls = $offsiteTask->startingKwUrls()->get(); 

        return response()->json($startingKwUrls);
    }

    public function deleteStartingKwUrl($id)
    {
        $kwUrl = StartingKwUrl::find($id);

        if (!$kwUrl) {
            return response()->json(['message' => 'Record Not Found'], 404);
        }

        $kwUrl->delete();

        return response()->json(['message' => 'Deleted Successfully']);
    }
}
