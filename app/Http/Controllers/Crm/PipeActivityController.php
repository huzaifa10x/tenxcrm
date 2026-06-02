<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Crm\Activity;
use App\Models\Crm\Deal;
use Illuminate\Http\Request;

class PipeActivityController extends Controller
{
    public function store(Request $request, Deal $deal)
    {
        // $this->authorize('view', $deal);

        $data = $request->validate([
            'type'     => 'required|in:' . implode(',', Activity::TYPES),
            'subject'  => 'required|string|max:255',
            'note'     => 'nullable|string',
            'due_date' => 'nullable|date',
            'due_time' => 'nullable',
            'location' => 'nullable|string|max:255',
            'user_id'  => 'nullable|exists:users,id',
        ]);

        $deal->activities()->create(array_merge($data, [
            'person_id'       => $deal->person_id,
            'organization_id' => $deal->organization_id,
            'user_id'         => $data['user_id'] ?? $request->user()->id,
            'created_by'      => $request->user()->id,
        ]));

        $deal->touchActivity();

        return back()->with('success', 'Activity scheduled.');
    }

    public function toggle(Request $request, Activity $activity)
    {
        // $this->authorize('view', $activity->deal);

        $activity->update([
            'done'    => ! $activity->done,
            'done_at' => $activity->done ? null : now(),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'done' => $activity->done]);
        }

        return back();
    }

    public function update(Request $request, Activity $activity)
    {
        // $this->authorize('update', $activity->deal);

        $activity->update($request->validate([
            'subject'  => 'sometimes|string|max:255',
            'note'     => 'nullable|string',
            'due_date' => 'nullable|date',
            'due_time' => 'nullable',
            'location' => 'nullable|string|max:255',
        ]));

        return back()->with('success', 'Activity updated.');
    }

    public function destroy(Activity $activity)
    {
        // $this->authorize('update', $activity->deal);
        $activity->delete();

        return back()->with('success', 'Activity deleted.');
    }
}
