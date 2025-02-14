<?php

namespace App\Http\Controllers;

use App\Models\OffsiteTask;
use App\Models\StartingKwUrl;
use App\Models\Task;
use Illuminate\Http\Request;

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
    public function store(Request $request)
    {
        $kwUrls = is_string($request->kw_url) ? json_decode($request->kw_url, true) : $request->kw_url;
        if (!empty($kwUrls) && is_array($kwUrls)) {
            foreach ($kwUrls as $kw) {
                if (isset($kw['keyword']) && isset($kw['url'])) {
                    $domain = parse_url($kw['url'], PHP_URL_HOST);

                    StartingKwUrl::create([
                        'keyword' => $kw['keyword'],
                        'url' => $kw['url'],
                        'domain' => $domain,
                        'task_id' => $request->task_id,
                        'project_id' => $request->project_id,
                        'offsite_task_id' => $request->offsite_task_id,
                    ]);
                }
            }
        }
        return response()->json(['message' => 'Links added successfully!']);
    }
}
