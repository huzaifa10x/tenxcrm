<?php

namespace App\Http\Controllers;

use App\Exports\ActivitiesExport;
use App\Imports\ActivitiesImport;
use App\Models\Activity;
use App\Models\Deal;
use App\Models\Lead;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ActivityController extends Controller
{
    /* ──────────────────────────── INDEX ──────────────────────────── */

    public function index(Request $request)
    {
        $filters = $request->only([
            'search', 'type', 'done', 'assigned_to', 'organization',
            'period', 'date_from', 'date_to',
        ]);

        // Default: show To-do items unless a done filter is explicitly applied
        if (!isset($filters['done']) || $filters['done'] === '') {
            $filters['done'] = 'To do';
        }
        if ($filters['done'] === 'all') {
            unset($filters['done']);
        }

        $activities = Activity::filter($filters)
            ->orderBy('due_date')
            ->orderBy('due_time')
            ->paginate(50)
            ->withQueryString();

        $assignees     = Activity::whereNotNull('assigned_to')->distinct()->orderBy('assigned_to')->pluck('assigned_to');
        $organizations = Activity::whereNotNull('organization')->distinct()->orderBy('organization')->pluck('organization');

        // Counts for tab badges
        $today    = now()->toDateString();
        $counts   = [
            'all'       => Activity::count(),
            'todo'      => Activity::where('done', 'To do')->count(),
            'overdue'   => Activity::where('done', 'To do')->where('due_date', '<', $today)->count(),
            'today'     => Activity::whereDate('due_date', $today)->count(),
            'tomorrow'  => Activity::whereDate('due_date', now()->addDay()->toDateString())->count(),
            'this_week' => Activity::whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'next_week' => Activity::whereBetween('due_date', [now()->addWeek()->startOfWeek(), now()->addWeek()->endOfWeek()])->count(),
        ];

        return view('activities.index', compact('activities', 'filters', 'assignees', 'organizations', 'counts'));
    }

    /* ──────────────────────────── STORE (modal AJAX or regular POST) ── */

    public function store(Request $request)
    {
        $data = $this->validateActivity($request);
        $activity = Activity::create($data);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'activity' => $activity]);
        }

        return redirect()->route('crm.activities.index')
            ->with('success', 'Activity scheduled.');
    }

    /* ──────────────────────────── SHOW ───────────────────────────── */

    public function show(Activity $activity)
    {
        return view('activities.show', compact('activity'));
    }

    /* ──────────────────────────── EDIT ───────────────────────────── */

    public function edit(Activity $activity)
    {
        $deals = Deal::orderBy('title')->get(['id', 'title']);
        $leads = Lead::orderBy('title')->get(['id', 'title']);

        // If requested via XHR (from the inline edit button), return bare modal fragment
        if (request()->ajax() || request()->wantsJson()) {
            return view('activities._form', compact('activity', 'deals', 'leads'))->render();
        }

        return view('activities.edit', compact('activity', 'deals', 'leads'));
    }

    public function update(Request $request, Activity $activity)
    {
        $data = $this->validateActivity($request);
        $activity->update($data);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('crm.activities.index')
            ->with('success', 'Activity updated.');
    }

    /* ──────────────────────────── DELETE ─────────────────────────── */

    public function destroy(Activity $activity)
    {
        $activity->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('crm.activities.index')
            ->with('success', 'Activity deleted.');
    }

    /* ──────────────────────────── MARK DONE ──────────────────────── */

    public function markDone(Activity $activity)
    {
        $activity->update([
            'done' => $activity->done === 'Done' ? 'To do' : 'Done',
        ]);

        if (request()->expectsJson()) {
            return response()->json(['done' => $activity->done]);
        }

        return back()->with('success', 'Activity status updated.');
    }

    /* ──────────────────────────── IMPORT ─────────────────────────── */

    public function importForm()
    {
        return view('activities.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:20480',
        ]);

        $import = new ActivitiesImport();
        Excel::import($import, $request->file('file'));

        return redirect()->route('crm.activities.index')
            ->with('success', "Import complete: {$import->importedCount} imported, {$import->skippedCount} skipped.");
    }

    /* ──────────────────────────── EXPORT ─────────────────────────── */

    public function export(Request $request)
    {
        $filters  = $request->only(['search', 'type', 'done', 'assigned_to', 'organization', 'period', 'date_from', 'date_to']);
        $filename = 'activities_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new ActivitiesExport($filters), $filename);
    }

    /* ──────────────────────────── HELPERS ────────────────────────── */

    private function validateActivity(Request $request): array
    {
        return $request->validate([
            'subject'       => 'required|string|max:255',
            'type'          => 'required|in:Call,Meeting,Task,Deadline,Email,Lunch',
            'done'          => 'nullable|in:To do,Done',
            'due_date'      => 'nullable|date',
            'due_time'      => 'nullable|date_format:H:i',
            'end_time'      => 'nullable|date_format:H:i',
            'duration'      => 'nullable|string|max:10',
            'deal_id'       => 'nullable|exists:deals,id',
            'lead_id'       => 'nullable|exists:leads,id',
            'deal_name'     => 'nullable|string|max:255',
            'contact_person'=> 'nullable|string|max:255',
            'email_work'    => 'nullable|email|max:255',
            'phone_work'    => 'nullable|string|max:50',
            'phone_mobile'  => 'nullable|string|max:50',
            'organization'  => 'nullable|string|max:255',
            'assigned_to'   => 'nullable|string|max:100',
            'priority'      => 'nullable|in:Normal,High',
            'location'      => 'nullable|string|max:255',
            'video_call_url'=> 'nullable|url|max:500',
            'description'   => 'nullable|string|max:2000',
            'note'          => 'nullable|string|max:5000',
        ]);
    }
}
