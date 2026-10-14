<x-app-layout>
    <x-slot name="header">My leave</x-slot>
    @include('attendance.partials.assets')

    @php
        $n = fn ($v) => $v === null ? '∞' : rtrim(rtrim(number_format($v, 1), '0'), '.');
        $statusTabs = ['' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'cancelled' => 'Cancelled'];
    @endphp

    <div class="xa">
        @include('attendance.partials.tabs', ['active' => 'my-leave'])

        <div class="xa-head">
            <div>
                <h1 class="xa-title">My leave</h1>
                <div class="xa-sub">Leave year {{ $yearLabel }} ({{ $yearRange[0]->format('d M Y') }} – {{ $yearRange[1]->format('d M Y') }}) · Only working days are counted</div>
            </div>
            <div class="xa-actions">
                <select class="xa-select" style="width:auto" aria-label="Leave year" onchange="location.href='{{ route('leaves.index') }}?year='+this.value">
                    @foreach ($years as $y => $label)
                        <option value="{{ $y }}" @selected($y == $year)>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="button" class="xa-btn xa-btn-primary" data-bs-toggle="modal" data-bs-target="#xaApply"><iconify-icon icon="solar:calendar-add-linear"></iconify-icon> Apply for leave</button>
            </div>
        </div>

        @if (session('success'))
            <div class="xa-callout mb-3">{{ session('success') }}</div>
        @endif

        {{-- Balances --}}
        <div class="xa-grid xa-grid-3 mb-3">
            @foreach ($summary as $b)
                @php
                    $ent = $b['entitlement'];
                    $usedPct = $ent ? min(100, $b['used'] / max($ent, 0.01) * 100) : 0;
                    $pendPct = $ent ? min(100 - $usedPct, $b['pending'] / max($ent, 0.01) * 100) : 0;
                @endphp
                <div class="xa-balance" style="--c: {{ $b['type']->color }}">
                    <div class="xa-balance-top">
                        <div class="xa-balance-name">{{ $b['type']->name }}</div>
                        @if (!$b['type']->is_paid)<span class="xa-badge xa-badge-weekend no-dot">Unpaid</span>@endif
                    </div>
                    @if ($ent !== null)
                        <div class="xa-balance-big">{{ $n($b['remaining']) }} <small>of {{ $n($ent) }} days left</small></div>
                        <div class="xa-balance-bar" role="img" aria-label="{{ $n($b['used']) }} used, {{ $n($b['pending']) }} pending of {{ $n($ent) }}">
                            <span style="width: {{ $usedPct }}%; background: {{ $b['type']->color }}"></span>
                            <span style="width: {{ $pendPct }}%; background: {{ $b['type']->color }}; opacity: .35"></span>
                        </div>
                        <div class="xa-balance-rows">
                            <div><b>{{ $n($b['allowance']) }}</b>Allowance</div>
                            <div><b>{{ $n($b['carried'] + $b['adjustment']) }}</b>Carry/adj.</div>
                            <div><b>{{ $n($b['used']) }}</b>Used</div>
                            <div><b>{{ $n($b['pending']) }}</b>Pending</div>
                        </div>
                        @if ($b['available'] < $b['remaining'])
                            <div class="xa-help mt-2">{{ $n(max(0, $b['available'])) }} available to request after pending requests.</div>
                        @endif
                    @else
                        <div class="xa-balance-big">{{ $n($b['used']) }} <small>days taken</small></div>
                        <div class="xa-help mt-3">No fixed limit — every request is reviewed{{ $b['pending'] ? ' · ' . $n($b['pending']) . ' pending' : '' }}.</div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="xa-grid xa-grid-main">
            {{-- Requests --}}
            <div class="xa-card">
                <div class="xa-card-head">
                    <h2 class="xa-card-title">My requests</h2>
                </div>
                <div class="xa-tabs px-3 mb-0" style="border-bottom:1px solid var(--x-border)">
                    @foreach ($statusTabs as $k => $label)
                        <a class="xa-tab {{ ($status ?? '') === $k ? 'active' : '' }}" href="{{ route('leaves.index', array_filter(['status' => $k, 'year' => request('year')])) }}">
                            {{ $label }}@if ($k && ($counts[$k] ?? 0))<span class="xa-count {{ $k === 'pending' ? 'is-hot' : '' }}">{{ $counts[$k] }}</span>@endif
                        </a>
                    @endforeach
                </div>
                <div class="xa-table-wrap">
                    <table class="xa-table">
                        <thead><tr><th>Dates</th><th>Type</th><th class="num">Days</th><th>Status</th><th class="xa-hide-sm">Reviewer note</th><th></th></tr></thead>
                        <tbody>
                            @forelse ($requests as $r)
                                <tr class="is-clickable" data-leave="{{ $r->id }}">
                                    <td>
                                        <div class="fw-semibold">{{ $r->start_date->format('d M Y') }}{{ $r->start_date->ne($r->end_date) ? ' – ' . $r->end_date->format('d M Y') : '' }}</div>
                                        <div class="xa-muted" style="font-size:.74rem">Applied {{ $r->created_at->format('d M Y') }}</div>
                                    </td>
                                    <td><span class="xa-balance-name" style="--c: {{ $r->type->color }}; font-weight:600">{{ $r->type->name }}</span></td>
                                    <td class="num">{{ $n($r->days) }}</td>
                                    <td><span class="xa-badge xa-badge-{{ $r->status }}">{{ ucfirst($r->status) }}</span></td>
                                    <td class="xa-hide-sm" style="max-width:220px"><span class="xa-muted">{{ Str::limit($r->review_comment, 60) ?: '—' }}</span></td>
                                    <td class="num">
                                        @if ($r->status === 'pending')
                                            <button type="button" class="xa-btn xa-btn-sm xa-btn-danger" data-cancel="{{ $r->id }}">Cancel</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6"><div class="xa-empty"><iconify-icon icon="solar:calendar-minimalistic-linear"></iconify-icon>No leave requests{{ $status ? ' with this status' : ' yet' }}.</div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($requests->hasPages())
                    <div class="xa-pager">{{ $requests->links() }}</div>
                @endif
            </div>

            <div class="xa-stack">
                <div class="xa-card">
                    <div class="xa-card-head"><h2 class="xa-card-title">Upcoming time off</h2></div>
                    <div class="xa-card-body">
                        @forelse ($upcoming as $u)
                            <div class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div>
                                    <div class="fw-semibold">{{ $u->start_date->format('D d M') }}{{ $u->start_date->ne($u->end_date) ? ' – ' . $u->end_date->format('D d M') : '' }}</div>
                                    <div class="xa-muted" style="font-size:.76rem">{{ $u->type->name }} · {{ $n($u->days) }} {{ Str::plural('day', $u->days) }}</div>
                                </div>
                                <span class="xa-badge xa-badge-approved">Approved</span>
                            </div>
                        @empty
                            <div class="xa-muted">Nothing booked.</div>
                        @endforelse
                    </div>
                </div>
                <div class="xa-callout is-info">
                    <strong>How it works</strong><br>
                    Weekends and public holidays inside your dates aren't deducted. Pending requests hold the days until they're reviewed; you can cancel a request while it's pending. Unused annual leave resets at the start of each leave year{{ $summary->firstWhere(fn ($b) => $b['type']->carry_forward_max > 0) ? ' (some types carry a few days forward)' : '' }}.
                </div>
            </div>
        </div>
    </div>

    {{-- Apply modal --}}
    <div class="modal fade xa-modal xa" id="xaApply" tabindex="-1" aria-labelledby="xaApplyTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" id="xaApplyForm" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="xaApplyTitle">Apply for leave</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body xa-stack">
                    <div class="xa-field">
                        <label for="aType">Leave type</label>
                        <select name="leave_type_id" id="aType" class="xa-select" required>
                            @foreach ($types as $t)
                                @php $bal = $summary->firstWhere('type.id', $t->id); @endphp
                                <option value="{{ $t->id }}" data-reason="{{ $t->requires_reason ? 1 : 0 }}">
                                    {{ $t->name }}{{ $bal && $bal['available'] !== null ? ' — ' . $n(max(0, $bal['available'])) . ' days available' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="xa-form-row">
                        <div class="xa-field">
                            <label for="aFrom">From</label>
                            <input type="date" name="start_date" id="aFrom" class="xa-input" required min="{{ now()->subDays(\App\Services\Leave\LeaveService::MAX_BACKDATE_DAYS)->toDateString() }}">
                        </div>
                        <div class="xa-field">
                            <label for="aTo">To</label>
                            <input type="date" name="end_date" id="aTo" class="xa-input" required>
                        </div>
                    </div>
                    <div data-preview class="xa-callout" hidden></div>
                    <div class="xa-field">
                        <label for="aReason">Reason <span data-reason-req class="text-danger" hidden>*</span></label>
                        <textarea name="reason" id="aReason" class="xa-textarea" maxlength="1000" placeholder="A short note for your manager"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="xa-btn" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="xa-btn xa-btn-primary">Submit request</button>
                </div>
            </form>
        </div>
    </div>

    @include('leaves.partials.detail-modal')

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('xaApplyForm');
        const preview = form.querySelector('[data-preview]');
        const urls = { preview: @json(route('leaves.preview')), store: @json(route('leaves.store')), cancel: @json(route('leaves.cancel', ['leave' => '__ID__'])) };

        if (location.hash === '#apply') bootstrap.Modal.getOrCreateInstance(document.getElementById('xaApply')).show();

        function syncReason() {
            const opt = form.leave_type_id.selectedOptions[0];
            form.querySelector('[data-reason-req]').hidden = !(opt && opt.dataset.reason === '1');
        }
        form.leave_type_id.addEventListener('change', () => { syncReason(); runPreview(); });
        syncReason();

        form.start_date.addEventListener('change', () => {
            if (!form.end_date.value || form.end_date.value < form.start_date.value) form.end_date.value = form.start_date.value;
            form.end_date.min = form.start_date.value;
            runPreview();
        });
        form.end_date.addEventListener('change', runPreview);

        let seq = 0;
        async function runPreview() {
            if (!form.start_date.value || !form.end_date.value || form.end_date.value < form.start_date.value) { preview.hidden = true; return; }
            const my = ++seq;
            try {
                const p = await XA.request(urls.preview, { body: { leave_type_id: form.leave_type_id.value, start_date: form.start_date.value, end_date: form.end_date.value }, quiet: true });
                if (my !== seq) return;
                let html = '', cls = '';
                if (!p.days) {
                    html = 'These dates are all weekends or holidays — no leave needed.'; cls = 'is-warn';
                } else {
                    html = '<strong>' + p.days + ' working ' + (p.days === 1 ? 'day' : 'days') + '</strong> will be deducted.';
                    p.years.forEach(y => {
                        if (y.available !== null) {
                            const after = y.available - y.days;
                            html += '<br>' + XA.esc(y.label) + ': ' + (after < 0
                                ? y.days + ' requested but only ' + Math.max(0, y.available) + ' available — <strong>not enough balance</strong>'
                                : Math.max(0, y.available) + ' available → ' + after + ' left after this request');
                            if (after < 0) cls = 'is-bad';
                        }
                    });
                }
                if (p.overlap) { html += '<br><strong>Overlaps an existing ' + XA.esc(p.overlap.status) + ' request.</strong>'; cls = 'is-bad'; }
                preview.className = 'xa-callout ' + cls;
                preview.innerHTML = html;
                preview.hidden = false;
            } catch (e) { preview.hidden = true; }
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = form.querySelector('[type="submit"]');
            XA.showErrors(form, {});
            XA.busy(btn, true, 'Submitting…');
            try {
                const data = await XA.request(urls.store, { body: new FormData(form) });
                XA.toast('success', data.success);
                bootstrap.Modal.getInstance(document.getElementById('xaApply')).hide();
                setTimeout(() => location.href = @json(route('leaves.index', ['status' => 'pending'])), 600);
            } catch (err) {
                XA.showErrors(form, err.errors);
            } finally {
                XA.busy(btn, false);
            }
        });

        document.addEventListener('click', async (e) => {
            const c = e.target.closest('[data-cancel]');
            if (!c) return;
            e.stopPropagation();
            if (!confirm('Cancel this leave request?')) return;
            XA.busy(c, true, '…');
            try {
                const data = await XA.request(urls.cancel.replace('__ID__', c.dataset.cancel));
                XA.toast('success', data.success);
                setTimeout(() => location.reload(), 500);
            } catch (err) { XA.busy(c, false); }
        }, true);
    });
    </script>
</x-app-layout>
