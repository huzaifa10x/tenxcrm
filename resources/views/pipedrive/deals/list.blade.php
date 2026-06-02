<x-app-layout>
    <x-slot name="header">{{ __('CRM — Deals (List)') }}</x-slot>

    @include('pipedrive._styles')

    <div class="crm-page">
        @if (session('success'))
            <div class="alert alert-success py-2" style="font-size:.85rem;">{{ session('success') }}</div>
        @endif

        <div class="crm-toolbar">
            <h1><i class="bi bi-list-ul me-2"></i>Deals</h1>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="btn-group crm-view-toggle">
                    <a href="{{ route('pipedrive.deals.index', ['view' => 'index']) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-kanban me-1"></i>Board</a>
                    <a href="{{ route('pipedrive.deals.index', ['view' => 'list']) }}" class="btn btn-sm btn-primary"><i class="bi bi-list-ul me-1"></i>List</a>
                </div>
                <a href="{{ route('pipedrive.deals.export') }}" class="btn btn-sm btn-outline-success"><i class="bi bi-download me-1"></i>Export</a>
                <a href="{{ route('pipedrive.deals.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Deal</a>
            </div>
        </div>

        <div class="crm-filter-bar">
            <div class="row g-2">
                <div class="col-lg-4 col-md-6">
                    <label class="form-label"><i class="bi bi-search me-1"></i>Search</label>
                    <input type="text" id="fSearch" class="form-control" placeholder="Deal, person, org…">
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label"><i class="bi bi-flag me-1"></i>Status</label>
                    <select id="fStatus" class="form-select">
                        <option value="">All</option>
                        <option value="open">Open</option>
                        <option value="won">Won</option>
                        <option value="lost">Lost</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label"><i class="bi bi-person-badge me-1"></i>Owner</label>
                    <select id="fOwner" class="form-select">
                        <option value="">All owners</option>
                        @foreach ($owners as $o)
                            <option value="{{ $o->id }}">{{ $o->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 d-flex align-items-end">
                    <button class="btn btn-primary btn-sm w-100" id="fApply"><i class="bi bi-funnel-fill me-1"></i>Apply</button>
                </div>
            </div>
        </div>

        <div class="crm-panel">
            <div class="crm-panel-head d-flex justify-content-between">
                <span>Deals (<span id="dealCount">{{ $deals->flatten()->count() }}</span>)</span>
                <span>Total: AED <span id="dealTotal">0.00</span></span>
            </div>
            <div class="table-responsive">
                <table class="table mb-0" style="font-size:.85rem;">
                    <thead style="background:#f8f9fc;">
                        <tr>
                            <th>Title</th><th>Stage</th><th>Status</th><th>Person</th>
                            <th>Organization</th><th>Owner</th><th>Value</th><th>Close</th><th></th>
                        </tr>
                    </thead>
                    <tbody id="dealsBody">
                        <tr><td colspan="9" class="crm-empty"><i class="bi bi-hourglass"></i>Loading…</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const body = document.getElementById('dealsBody');
            const badge = { open: 'secondary', won: 'success', lost: 'danger' };

            function load() {
                const params = new URLSearchParams({
                    search: fSearch.value, status: fStatus.value, owner_id: fOwner.value
                });
                fetch(`{{ route('pipedrive.deals.list') }}?${params}`, { headers: { 'Accept': 'application/json' }})
                    .then(r => r.json())
                    .then(data => {
                        document.getElementById('dealCount').textContent = data.total;
                        document.getElementById('dealTotal').textContent = data.totalValue;
                        if (!data.deals.length) {
                            body.innerHTML = `<tr><td colspan="9" class="crm-empty"><i class="bi bi-inbox"></i>No deals found</td></tr>`;
                            return;
                        }
                        body.innerHTML = data.deals.map(d => `
                            <tr style="cursor:pointer" onclick="location.href='${d.url}'">
                                <td class="fw-semibold">${d.title}</td>
                                <td><span class="badge" style="background:${d.stage_color||'#ccc'};color:#fff;">${d.stage||'—'}</span></td>
                                <td><span class="badge bg-${badge[d.status]||'secondary'}">${d.status}</span></td>
                                <td>${d.person||'—'}</td>
                                <td>${d.organization||'—'}</td>
                                <td>${d.owner||'—'}</td>
                                <td class="text-success fw-semibold">${d.currency} ${d.value}</td>
                                <td>${d.expected_close||'—'}</td>
                                <td><a href="${d.url}" class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation()"><i class="bi bi-eye"></i></a></td>
                            </tr>`).join('');
                    });
            }
            document.getElementById('fApply').addEventListener('click', load);
            [fSearch, fStatus, fOwner].forEach(el => el.addEventListener('keyup', e => { if (e.key === 'Enter') load(); }));
            load();
        });
    </script>
</x-app-layout>
