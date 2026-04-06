<?php

namespace App\Http\Controllers;

use App\Exports\LeadsExport;
use App\Imports\LeadsImport;
use App\Models\CrmNote;
use App\Models\Lead;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LeadController extends Controller
{
    /* ──────────────────────────── INDEX ──────────────────────────── */

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'label', 'owner', 'source_origin', 'date_from', 'date_to']);

        $leads = Lead::filter($filters)
            ->orderByDesc('lead_created_at')
            ->paginate(25)
            ->withQueryString();

        $owners  = Lead::whereNotNull('owner')->distinct()->orderBy('owner')->pluck('owner');
        $sources = Lead::whereNotNull('source_origin')->distinct()->orderBy('source_origin')->pluck('source_origin');

        return view('leads.index', compact('leads', 'filters', 'owners', 'sources'));
    }

    /* ──────────────────────────── CREATE ─────────────────────────── */

    public function create()
    {
        return view('leads.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'label'          => 'nullable|in:Cold,Warm,Hot',
            'source_origin'  => 'nullable|string|max:255',
            'owner'          => 'nullable|string|max:255',
            'lead_created_at'=> 'nullable|date',
            'next_activity_at'=> 'nullable|date',
            'notes'          => 'nullable|string',
        ]);

        $lead = Lead::create($data);

        return redirect()->route('leads.show', $lead)
            ->with('success', 'Lead created successfully.');
    }

    /* ──────────────────────────── SHOW ───────────────────────────── */

    public function show(Lead $lead)
    {
        $lead->load('crmNotes');
        return view('leads.show', compact('lead'));
    }

    /* ──────────────────────────── EDIT ───────────────────────────── */

    public function edit(Lead $lead)
    {
        return view('leads.edit', compact('lead'));
    }

    public function update(Request $request, Lead $lead)
    {
        $data = $request->validate([
            'title'           => 'required|string|max:255',
            'label'           => 'nullable|in:Cold,Warm,Hot',
            'source_origin'   => 'nullable|string|max:255',
            'owner'           => 'nullable|string|max:255',
            'lead_created_at' => 'nullable|date',
            'next_activity_at'=> 'nullable|date',
            'notes'           => 'nullable|string',
        ]);

        $lead->update($data);

        return redirect()->route('leads.show', $lead)
            ->with('success', 'Lead updated successfully.');
    }

    /* ──────────────────────────── DELETE ─────────────────────────── */

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('leads.index')
            ->with('success', 'Lead deleted.');
    }

    /* ──────────────────────────── STATUS / LABEL ─────────────────── */

    public function updateLabel(Request $request, Lead $lead)
    {
        $request->validate(['label' => 'required|in:Cold,Warm,Hot']);
        $lead->update(['label' => $request->label]);

        return back()->with('success', 'Label updated.');
    }

    /* ──────────────────────────── NOTES ──────────────────────────── */

    public function storeNote(Request $request, Lead $lead)
    {
        $request->validate(['content' => 'required|string|max:5000']);

        $lead->crmNotes()->create([
            'content' => $request->content,
            'author'  => auth()->user()?->name ?? 'System',
        ]);

        return back()->with('success', 'Note added.');
    }

    public function destroyNote(Lead $lead, CrmNote $note)
    {
        abort_unless($note->notable_id === $lead->id && $note->notable_type === Lead::class, 403);
        $note->delete();
        return back()->with('success', 'Note deleted.');
    }

    /* ──────────────────────────── IMPORT ─────────────────────────── */

    public function importForm()
    {
        return view('leads.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:20480',
        ]);

        $import = new LeadsImport();
        Excel::import($import, $request->file('file'));

        return redirect()->route('leads.index')
            ->with('success', "Import complete: {$import->importedCount} imported, {$import->skippedCount} skipped.");
    }

    /* ──────────────────────────── EXPORT ─────────────────────────── */

    public function export(Request $request)
    {
        $filters  = $request->only(['search', 'label', 'owner', 'source_origin', 'date_from', 'date_to']);
        $filename = 'leads_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new LeadsExport($filters), $filename);
    }
}
