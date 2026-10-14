<x-app-layout>
    <x-slot name="header">Leave requests</x-slot>
    @include('attendance.partials.assets')

    @php
        $n = fn ($v) => rtrim(rtrim(number_format((float) $v, 1), '0'), '.');
        $statusTabs = ['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'cancelled' => 'Cancelled', 'all' => 'All'];
        $keep = array_filter(\Illuminate\Support\Arr::except($filters, ['status']));
    @endphp

    <div class="xa">
        @include('attendance.partials.tabs', ['active' => 'leave-admin'])

        <div class="xa-head">
            <div>
                <h1 class="xa-title">Leave requests</h1>
                <div class="xa-sub">Review, approve or reject. Every decision is logged and the employee is notified.</div>
            </div>
            @if ($canPolicies)
                <div class="xa-actions"><a class="xa-btn" href="{{ route('leaves.policies') }}"><iconify-icon icon="solar:settings-linear"></iconify-icon> Policies &amp; balances</a></div>
            @endif
        </div>

        @if (session('success'))
            <div class="xa-callout mb-3">{{ session('success') }}</div>
        @endif

        <div class="xa-grid xa-grid-main">
            <div class="xa-card">
                <div class="xa-tabs px-3 mb-0 pt-1" style="border-bottom:1px solid var(--x-border)">
                    @foreach ($statusTabs as $k => $label)
                        <a class="xa-tab {{ $status === $k ? 'active' : '' }}" href="{{ route('leaves.admin', $keep + ['status' => $k]) }}">
                            {{ $label }}@if ($k !== 'all' && ($counts[$k] ?? 0))<span class="xa-count {{ $k === 'pending' ? 'is-hot' : '' }}">{{ $counts[$k] }}</span>@endif
                        </a>
                    @endforeach
                </div>
                <form class="xa-filters" method="GET" action="{{ route('leaves.admin') }}">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <div class="xa-field" style="flex:1;min-width:150px">
                        <label for="lQ">Search</label>
                        <div class="xa-search"><iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                            <input type="search" id="lQ" name="q" class="xa-input" value="{{ $filters['q'] ?? '' }}" placeholder="Employee name"></div>
                    </div>
                    <div class="xa-field">
                        <label for="lEmp">Employee</label>
                        <select id="lEmp" name="user_id" class="xa-select">
                            <option value="">Everyone</option>
                            @foreach ($employees as $e)<option value="{{ $e->id }}" @selected(($filters['user_id'] ?? '') == $e->id)>{{ $e->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="xa-field">
                        <label for="lDept">Department</label>
                        <select id="lDept" name="department" class="xa-select">
                            <option value="">All</option>
                            @foreach ($departments as $d)<option value="{{ $d->id }}" @selected(($filters['department'] ?? '') == $d->id)>{{ $d->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="xa-field">
                        <label for="lType">Type</label>
                        <select id="lType" name="leave_type_id" class="xa-select">
                            <option value="">All</option>
                            @foreach ($types as $t)<option value="{{ $t->id }}" @selected(($filters['leave_type_id'] ?? '') == $t->id)>{{ $t->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="xa-field">
                        <label for="lFrom">From</label>
                        <input type="date" id="lFrom" name="from" class="xa-input" value="{{ $filters['from'] ?? '' }}">
                    </div>
                    <div class="xa-field">
                        <label for="lTo">To</label>
                        <input type="date" id="lTo" name="to" class="xa-input" value="{{ $filters['to'] ?? '' }}">
                    </div>
                    <button class="xa-btn xa-btn-dark" type="submit">Filter</button>
                    @if ($keep)<a class="xa-btn" href="{{ route('leaves.admin', ['status' => $status]) }}">Reset</a>@endif
                </form>
                <div class="xa-table-wrap">
                    <table class="xa-table">
                        <thead><tr><th>Employee</th><th>Dates</th><th>Type</th><th class="num">Days</th><th>Status</th><th class="xa-hide-sm">Submitted</th><th></th></tr></thead>
                        <tbody>
                            @forelse ($requests as $r)
                                <tr class="is-clickable" data-leave="{{ $r->id }}">
                                    <td>
                                        <div class="xa-person">
                                            @include('attendance.partials.avatar', ['user' => $r->user])
                                            <span style="min-width:0">
                                                <span class="xa-person-name d-block">{{ $r->user->name }}</span>
                                                <span class="xa-person-meta d-block">{{ $r->user->departmentNames() ?: '—' }}</span>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold" style="white-space:nowrap">{{ $r->start_date->format('d M') }}{{ $r->start_date->ne($r->end_date) ? ' – ' . $r->end_date->format('d M') : '' }} {{ $r->end_date->format('Y') }}</div>
                                        @if ($r->reason)<div class="xa-muted" style="font-size:.74rem;max-width:240px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $r->reason }}</div>@endif
                                    </td>
                                    <td><span class="xa-balance-name" style="--c: {{ $r->type->color }}; font-weight:600; white-space:nowrap">{{ $r->type->name }}</span></td>
                                    <td class="num">{{ $n($r->days) }}</td>
                                    <td>
                                        <span class="xa-badge xa-badge-{{ $r->status }}">{{ ucfirst($r->status) }}</span>
                                        @if ($r->reviewer)<div class="xa-muted" style="font-size:.72rem">by {{ $r->reviewer->name }}</div>@endif
                                    </td>
                                    <td class="xa-hide-sm xa-muted" style="white-space:nowrap">{{ $r->created_at->diffForHumans() }}</td>
                                    <td class="num"><button type="button" class="xa-btn xa-btn-sm {{ $r->status === 'pending' ? 'xa-btn-primary' : '' }}" data-leave-open="{{ $r->id }}">{{ $r->status === 'pending' ? 'Review' : 'View' }}</button></td>
                                </tr>
                            @empty
                                <tr><td colspan="7"><div class="xa-empty"><iconify-icon icon="solar:inbox-line-linear"></iconify-icon>{{ $status === 'pending' ? 'All caught up — no pending requests.' : 'No requests match these filters.' }}</div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($requests->hasPages())<div class="xa-pager">{{ $requests->links() }}</div>@endif
            </div>

            <div class="xa-card">
                <div class="xa-card-head"><h2 class="xa-card-title">Upcoming absences</h2><span class="xa-card-note">Next 30 days</span></div>
                <div class="xa-card-body">
                    @forelse ($upcoming as $u)
                        <div class="d-flex align-items-center justify-content-between gap-2 py-2 {{ !$loop->last ? 'border-bottom' : '' }}" style="cursor:pointer" data-leave="{{ $u->id }}">
                            <div style="min-width:0">
                                <div class="fw-semibold">{{ $u->user->name }}</div>
                                <div class="xa-muted" style="font-size:.76rem">{{ $u->start_date->format('D d M') }}{{ $u->start_date->ne($u->end_date) ? ' – ' . $u->end_date->format('D d M') : '' }} · {{ $n($u->days) }}d</div>
                            </div>
                            <span class="xa-badge no-dot" style="background: {{ $u->type->color }}1a; color: {{ $u->type->color }}">{{ $u->type->name }}</span>
                        </div>
                    @empty
                        <div class="xa-muted">No approved leave in the next 30 days.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @include('leaves.partials.detail-modal')
</x-app-layout>
