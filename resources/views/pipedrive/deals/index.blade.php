<x-app-layout>
    <x-slot name="header">{{ __('PipeDrive — Deals') }}</x-slot>

    @include('pipedrive._styles')

    <div class="crm-page">
        @if (session('success'))
            <div class="alert alert-success py-2" style="font-size:.85rem;">{{ session('success') }}</div>
        @endif

        {{-- ── Toolbar ───────────────────────────────────────── --}}
        <div class="crm-toolbar">
            <h4><i class="bi bi-kanban me-2"></i>{{ $pipeline->name }}</h4>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                @if ($pipelines->count() > 1)
                    <select class="form-select form-select-sm w-auto" onchange="location.href='?pipeline='+this.value">
                        @foreach ($pipelines as $p)
                            <option value="{{ $p->id }}" @selected($p->id === $pipeline->id)>{{ $p->name }}</option>
                        @endforeach
                    </select>
                @endif

                <div class="btn-group crm-view-toggle">
                    <a href="{{ route('pipedrive.deals.index', ['view' => 'index', 'pipeline' => $pipeline->id]) }}"
                       class="btn btn-sm btn-primary"><i class="bi bi-kanban me-1"></i>Board</a>
                    <a href="{{ route('pipedrive.deals.index', ['view' => 'list', 'pipeline' => $pipeline->id]) }}"
                       class="btn btn-sm btn-outline-secondary"><i class="bi bi-list-ul me-1"></i>List</a>
                </div>

                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#crmFilters">
                    <i class="bi bi-funnel me-1"></i>Filters
                </button>

                <form action="{{ route('pipedrive.deals.import') }}" method="POST" enctype="multipart/form-data" class="d-inline" id="importForm">
                    @csrf
                    <input type="file" name="file" id="importFile" accept=".xlsx,.xls,.csv" class="d-none" onchange="document.getElementById('importForm').submit()">
                    <button type="button" class="btn btn-sm btn-success" onclick="document.getElementById('importFile').click()">
                        <i class="bi bi-upload me-1"></i>Import
                    </button>
                </form>

                <a href="{{ route('pipedrive.deals.export', request()->query()) }}" class="btn btn-sm btn-outline-success">
                    <i class="bi bi-download me-1"></i>Export
                </a>

                <a href="{{ route('pipedrive.deals.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Deal
                </a>
            </div>
        </div>

        {{-- ── Filters ───────────────────────────────────────── --}}
        <div class="collapse {{ request()->hasAny(['search','status','owner_id','label']) ? 'show' : '' }}" id="crmFilters">
            <form method="GET" class="crm-filter-bar">
                <input type="hidden" name="pipeline" value="{{ $pipeline->id }}">
                <div class="row g-2">
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label"><i class="bi bi-search me-1"></i>Search</label>
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Deal, person, org…">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label"><i class="bi bi-flag me-1"></i>Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            @foreach (['open','won','lost'] as $st)
                                <option value="{{ $st }}" @selected(request('status')===$st)>{{ ucfirst($st) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label"><i class="bi bi-person-badge me-1"></i>Owner</label>
                        <select name="owner_id" class="form-select">
                            <option value="">All owners</option>
                            @foreach ($owners as $o)
                                <option value="{{ $o->id }}" @selected(request('owner_id')==$o->id)>{{ $o->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label"><i class="bi bi-calendar-event me-1"></i>Close from</label>
                        <input type="date" name="close_from" class="form-control" value="{{ request('close_from') }}">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label"><i class="bi bi-calendar-check me-1"></i>Close to</label>
                        <input type="date" name="close_to" class="form-control" value="{{ request('close_to') }}">
                    </div>
                </div>
                <div class="d-flex gap-2 mt-2">
                    <button class="btn btn-primary btn-sm"><i class="bi bi-funnel-fill me-1"></i>Apply</button>
                    <a href="{{ route('pipedrive.deals.index') }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</a>
                </div>
            </form>
        </div>

        {{-- ── Board ─────────────────────────────────────────── --}}
        <div class="crm-board" id="crmBoard">
            @foreach ($stages as $stage)
                <div class="crm-col">
                    <div class="crm-col-head" style="border-top-color: {{ $stage->color }};">
                        <div class="col-name">{{ $stage->name }}</div>
                        <div class="col-meta">
                            AED {{ number_format($stageTotals[$stage->id]['value'], 0) }}
                            · {{ $stageTotals[$stage->id]['count'] }} {{ Str::plural('deal', $stageTotals[$stage->id]['count']) }}
                        </div>
                    </div>
                    <div class="crm-col-body crm-stage-list" data-stage-id="{{ $stage->id }}">
                        @forelse ($deals->get($stage->id, collect()) as $deal)
                            @include('pipedrive.deals._card', ['deal' => $deal])
                        @empty
                            <div class="text-muted text-center py-3" style="font-size:.75rem;opacity:.6;">Drop deals here</div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- SortableJS for drag & drop --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrf = '{{ csrf_token() }}';

            document.querySelectorAll('.crm-stage-list').forEach(function (col) {
                new Sortable(col, {
                    group: 'crm-deals',
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    onEnd: function (evt) {
                        const card     = evt.item;
                        const dealId   = card.dataset.dealId;
                        const newCol   = evt.to;
                        const stageId  = newCol.dataset.stageId;
                        const orderedIds = [...newCol.querySelectorAll('.crm-card')].map(c => c.dataset.dealId);

                        fetch(`{{ url('pipedrive/deals') }}/${dealId}/move`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ stage_id: stageId, ordered_ids: orderedIds })
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (!data.success) { alert(data.message || 'Could not move deal'); location.reload(); }
                        })
                        .catch(() => { alert('Network error — reloading.'); location.reload(); });
                    }
                });
            });
        });
    </script>
</x-app-layout>
