<?php

namespace App\Http\Controllers\Crm;

use App\Exports\PipedriveDealsExport;
use App\Http\Controllers\Controller;
use App\Imports\PipedriveDealsImport;
use App\Models\Crm\Deal;
use App\Models\Crm\DealStageLog;
use App\Models\Crm\Organization;
use App\Models\Crm\Person;
use App\Models\Crm\Pipeline;
use App\Models\Crm\Stage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PipedriveDealController extends Controller
{
    /** Kanban board (default) or table view. */
    public function index(Request $request)
    {
        $user      = $request->user();
        $pipelines = Pipeline::orderBy('position')->get();
        $pipeline  = $request->filled('pipeline')
            ? $pipelines->firstWhere('id', (int) $request->pipeline) ?? Pipeline::default()
            : Pipeline::default();

        $stages = $pipeline->stages()->orderBy('position')->get();

        $deals = $this->filteredQuery($request, $pipeline)
            ->with(['person', 'organization', 'owner', 'stage'])
            ->orderBy('position')
            ->get()
            ->groupBy('stage_id');

        // per-stage totals for the column headers
        $stageTotals = [];
        foreach ($stages as $stage) {
            $bucket = $deals->get($stage->id, collect());
            $stageTotals[$stage->id] = [
                'count' => $bucket->count(),
                'value' => $bucket->sum('value'),
            ];
        }

        $owners = User::whereHas('roles', fn($q) =>
        $q->whereIn('name', ['ADMIN', 'SALES MANAGER']))->orderBy('name')->get();

        $view = $request->get('view', 'board');

        return view("pipedrive.deals.index", compact(
            'pipelines',
            'pipeline',
            'stages',
            'deals',
            'stageTotals',
            'owners'
        ));
    }

    /** AJAX JSON used by the table view filters. */
    public function list(Request $request)
    {
        $pipeline = Pipeline::default();
        $deals = $this->filteredQuery($request, null)
            ->with(['person', 'organization', 'owner', 'stage'])
            ->latest()
            ->get()
            ->map(fn(Deal $d) => [
                'id'            => $d->id,
                'title'         => $d->title,
                'value'         => number_format((float) $d->value, 2),
                'currency'      => $d->currency,
                'status'        => $d->status,
                'stage'         => $d->stage?->name,
                'stage_color'   => $d->stage?->color,
                'person'        => $d->person?->name,
                'organization'  => $d->organization?->name,
                'owner'         => $d->owner?->name,
                'label'         => $d->label,
                'expected_close' => optional($d->expected_close_date)->format('M d, Y'),
                'url'           => route('pipedrive.deals.show', $d->id),
            ]);

        return response()->json([
            'deals'      => $deals,
            'total'      => $deals->count(),
            'totalValue' => number_format((float) $this->filteredQuery($request, null)->sum('value'), 2),
        ]);
    }

    private function filteredQuery(Request $request, ?Pipeline $pipeline)
    {
        $query = Deal::query()->visibleTo($request->user());

        if ($pipeline) {
            $query->where('pipeline_id', $pipeline->id);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                    ->orWhereHas('person', fn($p) => $p->where('name', 'like', "%{$s}%")
                        ->orWhere('email', 'like', "%{$s}%")
                        ->orWhere('phone', 'like', "%{$s}%"))
                    ->orWhereHas('organization', fn($o) => $o->where('name', 'like', "%{$s}%"));
            });
        }
        if ($request->filled('status'))   $query->where('status', $request->status);
        if ($request->filled('stage_id')) $query->where('stage_id', $request->stage_id);
        if ($request->filled('owner_id')) $query->where('owner_id', $request->owner_id);
        if ($request->filled('label'))    $query->where('label', $request->label);
        if ($request->filled('close_from')) $query->whereDate('expected_close_date', '>=', $request->close_from);
        if ($request->filled('close_to'))   $query->whereDate('expected_close_date', '<=', $request->close_to);

        return $query;
    }

    public function create(Request $request)
    {
        $pipeline = Pipeline::default();
        $stages   = $pipeline->stages;
        $owners   = User::whereHas('roles', fn($q) =>
        $q->whereIn('name', ['ADMIN', 'SALES MANAGER']))->orderBy('name')->get();

        return view('pipedrive.deals.create', compact('pipeline', 'stages', 'owners'));
    }

    public function store(Request $request)
    {
        $data = $this->validateDeal($request);

        DB::transaction(function () use ($request, $data) {
            $person       = $this->resolvePerson($request);
            $organization = $this->resolveOrganization($request);
            $stage        = Stage::findOrFail($data['stage_id']);

            $deal = Deal::create([
                'title'               => $data['title'],
                'value'               => $data['value'] ?? 0,
                'currency'            => $data['currency'] ?? 'AED',
                'pipeline_id'         => $stage->pipeline_id,
                'stage_id'            => $stage->id,
                'person_id'           => $person?->id,
                'organization_id'     => $organization?->id,
                'owner_id'            => $this->resolveOwner($request),
                'created_by'          => $request->user()->id,
                'status'              => $stage->impliedStatus(),
                'label'               => $data['label'] ?? null,
                'expected_close_date' => $data['expected_close_date'] ?? null,
                'source_channel'      => $data['source_channel'] ?? null,
                'source_channel_id'   => $data['source_channel_id'] ?? null,
                'source_origin'       => 'Manually created',
                'visible_to'          => $data['visible_to'] ?? 'owner',
                'stage_changed_at'    => now(),
                'last_activity_at'    => now(),
            ]);

            if ($person) {
                $deal->participants()->syncWithoutDetaching([$person->id]);
            }

            DealStageLog::create([
                'deal_id'    => $deal->id,
                'stage_id'   => $stage->id,
                'user_id'    => $request->user()->id,
                'entered_at' => now(),
            ]);
        });

        return redirect()->route('pipedrive.deals.index')
            ->with('success', 'Deal created successfully.');
    }

    public function show(Deal $deal)
    {
        $deal->load([
            'person.organization',
            'organization',
            'owner',
            'creator',
            'participants',
            'followers',
            'pipeline.stages',
            'activities.user',
            'notes.user',
            'stageLogs.stage',
        ]);

        $stages = $deal->pipeline->stages;

        // build a per-stage "days spent" map from the logs (Pipedrive-style timeline)
        $stageDurations = [];
        foreach ($deal->stageLogs->groupBy('stage_id') as $stageId => $logs) {
            $stageDurations[$stageId] = $logs->sum(fn($l) => $l->durationDays());
        }

        return view('pipedrive.deals.show', compact('deal', 'stages', 'stageDurations'));
    }

    public function edit(Deal $deal)
    {
        $pipeline = $deal->pipeline;
        $stages   = $pipeline->stages;
        $owners   = User::whereHas('roles', fn($q) =>
        $q->whereIn('name', ['ADMIN', 'SALES MANAGER']))->orderBy('name')->get();

        return view('pipedrive.deals.edit', compact('deal', 'pipeline', 'stages', 'owners'));
    }

    public function update(Request $request, Deal $deal)
    {
        $data = $this->validateDeal($request);

        $person       = $this->resolvePerson($request);
        $organization = $this->resolveOrganization($request);

        $deal->update([
            'title'               => $data['title'],
            'value'               => $data['value'] ?? 0,
            'currency'            => $data['currency'] ?? 'AED',
            'person_id'           => $person?->id ?? $deal->person_id,
            'organization_id'     => $organization?->id ?? $deal->organization_id,
            'owner_id'            => $this->resolveOwner($request),
            'label'               => $data['label'] ?? null,
            'expected_close_date' => $data['expected_close_date'] ?? null,
            'source_channel'      => $data['source_channel'] ?? null,
            'source_channel_id'   => $data['source_channel_id'] ?? null,
            'visible_to'          => $data['visible_to'] ?? 'owner',
        ]);

        return redirect()->route('pipedrive.deals.show', $deal)
            ->with('success', 'Deal updated successfully.');
    }

    public function destroy(Deal $deal)
    {
        $deal->delete();

        return redirect()->route('pipedrive.deals.index')
            ->with('success', 'Deal deleted.');
    }

    /** Drag-and-drop: move a deal to a new stage and re-order the column. */
    public function move(Request $request, Deal $deal)
    {
        $request->validate([
            'stage_id'    => 'required|exists:crm_stages,id',
            'ordered_ids' => 'nullable|array',
        ]);

        $newStage = Stage::findOrFail($request->stage_id);

        DB::transaction(function () use ($deal, $newStage, $request) {
            if ($deal->stage_id !== $newStage->id) {
                // close the previous stage log, open a new one
                DealStageLog::where('deal_id', $deal->id)->whereNull('left_at')
                    ->update(['left_at' => now()]);

                DealStageLog::create([
                    'deal_id'    => $deal->id,
                    'stage_id'   => $newStage->id,
                    'user_id'    => $request->user()->id,
                    'entered_at' => now(),
                ]);

                $deal->stage_id         = $newStage->id;
                $deal->pipeline_id      = $newStage->pipeline_id;
                $deal->status           = $newStage->impliedStatus();
                $deal->stage_changed_at = now();
                $deal->last_activity_at = now();

                if ($newStage->is_won) $deal->won_date = now();
                if ($newStage->is_lost) $deal->lost_date = now();
            }

            // re-order the destination column if the client sent the new order
            if ($request->filled('ordered_ids')) {
                foreach ($request->ordered_ids as $pos => $id) {
                    Deal::where('id', $id)->update(['position' => $pos]);
                }
            }

            $deal->save();
        });

        return response()->json([
            'success' => true,
            'status'  => $deal->status,
            'message' => "Moved to {$newStage->name}.",
        ]);
    }

    public function markWon(Request $request, Deal $deal)
    {
        $wonStage = $deal->pipeline->stages()->where('is_won', true)->first();

        $deal->update([
            'status'           => 'won',
            'won_date'         => now(),
            'stage_id'         => $wonStage->id ?? $deal->stage_id,
            'stage_changed_at' => now(),
            'last_activity_at' => now(),
        ]);

        return back()->with('success', 'Deal marked as won. 🎉');
    }

    public function markLost(Request $request, Deal $deal)
    {
        $request->validate(['lost_reason' => 'nullable|string|max:255']);

        $lostStage = $deal->pipeline->stages()->where('is_lost', true)->first();

        $deal->update([
            'status'           => 'lost',
            'lost_date'        => now(),
            'lost_reason'      => $request->lost_reason,
            'stage_id'         => $lostStage->id ?? $deal->stage_id,
            'stage_changed_at' => now(),
            'last_activity_at' => now(),
        ]);

        return back()->with('success', 'Deal marked as lost.');
    }

    // ── Excel import / export ───────────────────────────────────
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls,csv,txt']);

        Excel::import(new PipedriveDealsImport($request->user()), $request->file('file'));

        return back()->with('success', 'Deals imported successfully.');
    }

    public function export(Request $request)
    {
        return Excel::download(
            new PipedriveDealsExport($request->user(), $request->all()),
            'deals_' . now()->format('Y-m-d_His') . '.xlsx'
        );
    }

    // ── Helpers ─────────────────────────────────────────────────
    private function validateDeal(Request $request): array
    {
        return $request->validate([
            'title'               => 'required|string|max:255',
            'value'               => 'nullable|numeric|min:0',
            'currency'            => 'nullable|string|max:8',
            'stage_id'            => 'required|exists:crm_stages,id',
            'label'               => 'nullable|string|max:60',
            'expected_close_date' => 'nullable|date',
            'source_channel'      => 'nullable|string|max:120',
            'source_channel_id'   => 'nullable|string|max:120',
            'visible_to'          => 'nullable|in:owner,everyone',
            'owner_id'            => 'nullable|exists:users,id',
            // inline person / organization
            'person_name'         => 'nullable|string|max:255',
            'person_phone'        => 'nullable|string|max:60',
            'person_email'        => 'nullable|email|max:255',
            'organization_name'   => 'nullable|string|max:255',
        ]);
    }

    /** ADMIN may assign any owner; everyone else owns their own deals. */
    private function resolveOwner(Request $request): int
    {
        if ($request->user()->hasRole('ADMIN') && $request->filled('owner_id')) {
            return (int) $request->owner_id;
        }
        return $request->user()->id;
    }

    private function resolvePerson(Request $request): ?Person
    {
        if ($request->filled('person_id')) {
            return Person::find($request->person_id);
        }
        if ($request->filled('person_name')) {
            return Person::firstOrCreate(
                ['name' => $request->person_name],
                [
                    'phone'    => $request->person_phone,
                    'email'    => $request->person_email,
                    'owner_id' => $request->user()->id,
                ]
            );
        }
        return null;
    }

    private function resolveOrganization(Request $request): ?Organization
    {
        if ($request->filled('organization_id')) {
            return Organization::find($request->organization_id);
        }
        if ($request->filled('organization_name')) {
            return Organization::firstOrCreate(
                ['name' => $request->organization_name],
                ['owner_id' => $request->user()->id]
            );
        }
        return null;
    }
}