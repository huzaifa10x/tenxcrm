{{-- Admin overview: monthly attendance performance (current month by default). --}}
@include('attendance.partials.assets')
@php
    $__departments = \App\Models\Role::orderBy('name')->get(['id', 'name']);
    $__employees = \App\Models\User::tracked()->orderBy('name')->get(['id', 'name']);
@endphp
<div class="xa" id="xaDashPerf">
    <div class="xa-head" style="margin-bottom:12px">
        <div>
            <div class="xa-card-title">Attendance performance</div>
            <div class="xa-sub">Per employee, based on the punch records and company working days</div>
        </div>
        <div class="xa-actions">
            <a class="xa-btn xa-btn-sm" href="{{ route('attendance.index') }}">Full report</a>
            <a class="xa-btn xa-btn-sm" href="{{ route('leaves.admin') }}">Leave requests</a>
        </div>
    </div>
    <form class="xa-filters xa-card mb-3" data-perf-form style="border-radius:12px">
        <div class="xa-field" style="flex:1;min-width:150px">
            <label for="dpQ">Search</label>
            <div class="xa-search"><iconify-icon icon="solar:magnifer-linear"></iconify-icon><input type="search" id="dpQ" name="q" class="xa-input" placeholder="Employee name"></div>
        </div>
        <div class="xa-field">
            <label for="dpDept">Department</label>
            <select id="dpDept" name="department" class="xa-select">
                <option value="">All</option>
                @foreach ($__departments as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach
            </select>
        </div>
        <div class="xa-field">
            <label for="dpEmp">Employee</label>
            <select id="dpEmp" name="user_id" class="xa-select">
                <option value="">Everyone</option>
                @foreach ($__employees as $e)<option value="{{ $e->id }}">{{ $e->name }}</option>@endforeach
            </select>
        </div>
        <div class="xa-field">
            <label for="dpFrom">From</label>
            <input type="date" id="dpFrom" name="from" class="xa-input" value="{{ now()->startOfMonth()->toDateString() }}">
        </div>
        <div class="xa-field">
            <label for="dpTo">To</label>
            <input type="date" id="dpTo" name="to" class="xa-input" value="{{ now()->endOfMonth()->toDateString() }}">
        </div>
        <button type="submit" class="xa-btn xa-btn-dark">Apply</button>
    </form>
    <div data-perf-body><div class="xa-empty">Loading attendance…</div></div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const root = document.getElementById('xaDashPerf');
    if (!root) return;
    const form = root.querySelector('[data-perf-form]');
    const body = root.querySelector('[data-perf-body]');
    const url = @json(route('attendance.performance'));
    let ctrl;

    async function load() {
        if (ctrl) ctrl.abort();
        ctrl = new AbortController();
        const params = new URLSearchParams(new FormData(form));
        body.style.opacity = .5;
        try {
            const res = await fetch(url + '?' + params, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal: ctrl.signal, credentials: 'same-origin' });
            if (!res.ok) throw new Error(res.status === 422 ? 'Check the date range.' : 'Could not load attendance.');
            body.innerHTML = await res.text();
            XA.sortable(body.querySelector('[data-perf-table]'));
        } catch (e) {
            if (e.name !== 'AbortError') body.innerHTML = '<div class="xa-empty">' + XA.esc(e.message) + '</div>';
        } finally {
            body.style.opacity = 1;
        }
    }
    form.addEventListener('submit', (e) => { e.preventDefault(); load(); });
    ['department', 'user_id'].forEach(n => form[n].addEventListener('change', load));
    let t;
    form.q.addEventListener('input', () => { clearTimeout(t); t = setTimeout(load, 350); });
    load();
});
</script>
