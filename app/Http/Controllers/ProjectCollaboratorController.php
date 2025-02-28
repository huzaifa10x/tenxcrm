<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectCollaborator;
use App\Models\User;
use App\Notifications\CustomNotification;

class ProjectCollaboratorController extends Controller
{
    public function index($project_id)
    {
        $collaborators = ProjectCollaborator::with('user')->where('project_id', $project_id)->get();
        return response()->json($collaborators);
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id'
        ]);

        if (ProjectCollaborator::where('project_id', $request->project_id)
            ->where('user_id', $request->user_id)
            ->exists()
        ) {
            return response()->json(['message' => 'User is already a collaborator'], 400);
        }

        $collaborator = ProjectCollaborator::create($request->all());
        $user = User::find($request->user_id);

        // Notify user
        $user->notify(new CustomNotification(
            "You have been added as a collaborator",
            auth()->user()->name . " has added you as a collaborator on project: " . $collaborator->project->title,
            url("projects/{$request->project_id}")
        ));

        return response()->json(['message' => 'Collaborator added successfully']);
    }

    public function destroy($id)
    {
        $collaborator = ProjectCollaborator::findOrFail($id);
        $collaborator->delete();

        return response()->json(['message' => 'Collaborator removed successfully']);
    }
}
