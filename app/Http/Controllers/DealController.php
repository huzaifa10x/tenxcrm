<?php

namespace App\Http\Controllers;

use App\Exports\DealsExport;
use App\Imports\DealsImport;
use App\Models\CrmNote;
use App\Models\Deal;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DealController extends Controller
{
    /* ──────────────────────────── INDEX ──────────────────────────── */

    public function index(Request $request)
    {
        $filters = $request->only([
            'search', 'status', 'stage', 'owner', 'pipeline',
            'source_origin', 'date_from', 'date_to', 'value_min', 'value_max',
        ]);

        $deals = Deal::filter($filters)
            ->orderByDesc('deal_created_at')
            ->paginate(25)
            ->withQueryString();

        $owners    = Deal::whereNotNull('owner')->distinct()->orderBy('owner')->pluck('owner');
        $pipelines = Deal::whereNotNull('pipeline')->distinct()->orderBy('pipeline')->pluck('pipeline');
        $sources   = Deal::whereNotNull('source_origin')->distinct()->orderBy('source_origin')->pluck('source_origin');

        // Summary stats for dashboard cards
        $stats = [
            'total'     => Deal::filter($filters)->count(),
            'open'      => Deal::filter($filters)->where('status', 'Open')->count(),
            'won'       => Deal::filter($filters)->where('status', 'Won')->count(),
            'lost'      => Deal::filter($filters)->where('status', 'Lost')->count(),
            'total_val' => Deal::filter($filters)->where('status', 'Open')->sum('value'),
        ];

        return view('deals.index', compact('deals', 'filters', 'owners', 'pipelines', 'sources', 'stats'));
    }

    /* ──────────────────────────── CREATE ─────────────────────────── */

    public function create()
    {
        return view('deals.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateDeal($request);
        $deal = Deal::create($data);

        return redirect()->route('crm.deals.show', $deal)
            ->with('success', 'Deal created successfully.');
    }

    /* ──────────────────────────── SHOW ───────────────────────────── */

    public function show(Deal $deal)
    {
        $deal->load('crmNotes');
        return view('deals.show', compact('deal'));
    }

    /* ──────────────────────────── EDIT ───────────────────────────── */

    public function edit(Deal $deal)
    {
        return view('deals.edit', compact('deal'));
    }

    public function update(Request $request, Deal $deal)
    {
        $data = $this->validateDeal($request);
        $deal->update($data);

        return redirect()->route('crm.deals.show', $deal)
            ->with('success', 'Deal updated successfully.');
    }

    /* ──────────────────────────── DELETE ─────────────────────────── */

    public function destroy(Deal $deal)
    {
        $deal->delete();
        return redirect()->route('crm.deals.index')
            ->with('success', 'Deal deleted.');
    }

    /* ──────────────────────────── STATUS UPDATE ──────────────────── */

    public function updateStatus(Request $request, Deal $deal)
    {
        $request->validate([
            'status'      => 'required|in:Open,Won,Lost',
            'lost_reason' => 'nullable|required_if:status,Lost|string|max:1000',
        ]);

        $deal->update([
            'status'      => $request->status,
            'lost_reason' => $request->status === 'Lost' ? $request->lost_reason : $deal->lost_reason,
            'won_time'    => $request->status === 'Won' ? now() : $deal->won_time,
            'lost_time'   => $request->status === 'Lost' ? now() : $deal->lost_time,
        ]);

        return back()->with('success', "Deal marked as {$request->status}.");
    }

    public function updateStage(Request $request, Deal $deal)
    {
        $request->validate(['stage' => 'required|string|max:100']);
        $deal->update(['stage' => $request->stage, 'last_stage_change' => now()]);

        return back()->with('success', 'Stage updated.');
    }

    /* ──────────────────────────── NOTES ──────────────────────────── */

    public function storeNote(Request $request, Deal $deal)
    {
        $request->validate(['content' => 'required|string|max:5000']);

        $deal->crmNotes()->create([
            'content' => $request->content,
            'author'  => auth()->user()?->name ?? 'System',
        ]);

        return back()->with('success', 'Note added.');
    }

    public function destroyNote(Deal $deal, CrmNote $note)
    {
        abort_unless($note->notable_id === $deal->id && $note->notable_type === Deal::class, 403);
        $note->delete();
        return back()->with('success', 'Note deleted.');
    }

    /* ──────────────────────────── IMPORT ─────────────────────────── */

    public function importForm()
    {
        return view('deals.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:51200',
        ]);

        $import = new DealsImport();
        Excel::import($import, $request->file('file'));

        return redirect()->route('crm.deals.index')
            ->with('success', "Import complete: {$import->importedCount} imported, {$import->skippedCount} skipped.");
    }

    /* ──────────────────────────── EXPORT ─────────────────────────── */

    public function export(Request $request)
    {
        $filters  = $request->only([
            'search', 'status', 'stage', 'owner', 'pipeline',
            'source_origin', 'date_from', 'date_to', 'value_min', 'value_max',
        ]);
        $filename = 'deals_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new DealsExport($filters), $filename);
    }

    /* ──────────────────────────── HELPERS ────────────────────────── */

    private function validateDeal(Request $request): array
    {
        return $request->validate([
            'title'               => 'required|string|max:255',
            'value'               => 'nullable|numeric|min:0',
            'currency'            => 'nullable|string|max:10',
            'organization'        => 'nullable|string|max:255',
            'contact_person'      => 'nullable|string|max:255',
            'owner'               => 'nullable|string|max:255',
            'pipeline'            => 'nullable|string|max:255',
            'stage'               => 'nullable|string|max:100',
            'status'              => 'nullable|in:Open,Won,Lost',
            'label'               => 'nullable|string|max:100',
            'probability'         => 'nullable|numeric|min:0|max:100',
            'source_origin'       => 'nullable|string|max:255',
            'source_channel'      => 'nullable|string|max:255',
            'lost_reason'         => 'nullable|string|max:1000',
            'product_name'        => 'nullable|string|max:255',
            'product_amount'      => 'nullable|numeric|min:0',
            'product_quantity'    => 'nullable|integer|min:0',
            'notes'               => 'nullable|string',
            'expected_close_date' => 'nullable|date',
            'next_activity_date'  => 'nullable|date',
        ]);
    }
}
