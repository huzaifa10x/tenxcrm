<x-app-layout>
    <x-slot name="header">Leave policies</x-slot>
    @include('attendance.partials.assets')

    @php
        $n = fn ($v) => $v === null ? '∞' : rtrim(rtrim(number_format((float) $v, 1), '0'), '.');
        $months = collect(range(1, 12))->mapWithKeys(fn ($m) => [$m => \Carbon\Carbon::create(2000, $m, 1)->format('F')]);
    @endphp

    <div class="xa">
        @include('attendance.partials.tabs', ['active' => 'policies'])

        <div class="xa-head">
            <div>
                <h1 class="xa-title">Leave policies &amp; balances</h1>
                <div class="xa-sub">Changes apply to the current leave year onwards. Past years keep the allowance they had.</div>
            </div>
        </div>

        <div class="xa-grid xa-grid-2 mb-3">
            {{-- Company allowance --}}
            <form class="xa-card" id="xaAllowance" novalidate>
                <div class="xa-card-head"><h2 class="xa-card-title">Annual leave allowance</h2></div>
                <div class="xa-card-body xa-stack">
                    <div class="xa-form-row">
                        <div class="xa-field">
                            <label for="pAllow">Days per employee per year</label>
                            <input type="number" id="pAllow" name="annual_leave_allowance" class="xa-input" min="0" max="365" step="1" value="{{ $settings->annual_leave_allowance ?? 12 }}" required>
                            <div class="xa-help">Default 12. Set 24 (or any number) — no code change needed.</div>
                        </div>
                        <div class="xa-field">
                            <label for="pMonth">Leave year starts</label>
                            <select id="pMonth" name="leave_year_start_month" class="xa-select">
                                @foreach ($months as $m => $label)
                                    <option value="{{ $m }}" @selected(($settings->leave_year_start_month ?? 1) == $m)>1 {{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="xa-help">Balances reset on this date each year.</div>
                        </div>
                    </div>
                    <div class="xa-callout is-info">
                        <strong>Reset rule:</strong> each leave year starts with the full allowance. Unused days are dropped, except for leave types with a carry-forward limit (up to that many days move to the next year). Leave that crosses the year boundary is split between the two years.
                    </div>
                    <div class="d-flex justify-content-end"><button type="submit" class="xa-btn xa-btn-primary">Save allowance</button></div>
                </div>
            </form>

            {{-- Tracking --}}
            <div class="xa-card">
                <div class="xa-card-head">
                    <h2 class="xa-card-title">Who is tracked in attendance</h2>
                    <span class="xa-card-note">Untracked people get no absences and are left out of reports</span>
                </div>
                <div class="xa-table-wrap" style="max-height:300px">
                    <table class="xa-table">
                        <tbody>
                            @foreach ($people as $p)
                                <tr>
                                    <td>
                                        <div class="xa-person">
                                            @include('attendance.partials.avatar', ['user' => $p])
                                            <span style="min-width:0"><span class="xa-person-name d-block">{{ $p->name }}</span><span class="xa-person-meta d-block">{{ $p->departmentNames() ?: $p->email }}</span></span>
                                        </div>
                                    </td>
                                    <td class="num">
                                        <label class="xa-switch" title="Track attendance">
                                            <input type="checkbox" data-track="{{ $p->id }}" @checked($p->track_attendance) @disabled(!$canManageAttendance) aria-label="Track attendance for {{ $p->name }}">
                                            <span></span>
                                        </label>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Leave types --}}
        <div class="xa-card mb-3">
            <div class="xa-card-head">
                <h2 class="xa-card-title">Leave types</h2>
                <button type="button" class="xa-btn xa-btn-dark xa-btn-sm" data-type-edit="new"><iconify-icon icon="solar:add-circle-linear"></iconify-icon> Add type</button>
            </div>
            <div class="xa-table-wrap">
                <table class="xa-table">
                    <thead><tr><th>Type</th><th>Allowance</th><th class="num">Carry forward</th><th>Paid</th><th>Over balance</th><th>Reason</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                        @foreach ($types as $t)
                            <tr class="{{ $t->is_active ? '' : 'is-muted' }}">
                                <td><span class="xa-balance-name" style="--c: {{ $t->color }}">{{ $t->name }}</span></td>
                                <td>
                                    @if ($t->allowance_source === 'annual') {{ $n($settings->annual_leave_allowance ?? 12) }} days <span class="xa-muted">(company allowance)</span>
                                    @elseif ($t->allowance_source === 'fixed') {{ $n($t->days_per_year) }} days
                                    @else <span class="xa-muted">No limit</span> @endif
                                </td>
                                <td class="num">{{ $t->isLimited() && $t->carry_forward_max > 0 ? 'up to ' . $n($t->carry_forward_max) : '—' }}</td>
                                <td>{{ $t->is_paid ? 'Paid' : 'Unpaid' }}</td>
                                <td>{{ !$t->isLimited() ? '—' : ($t->allow_negative ? 'Allowed' : 'Blocked') }}</td>
                                <td>{{ $t->requires_reason ? 'Required' : 'Optional' }}</td>
                                <td><span class="xa-badge {{ $t->is_active ? 'xa-badge-approved' : 'xa-badge-cancelled' }}">{{ $t->is_active ? 'Active' : 'Inactive' }}</span></td>
                                <td class="num"><button type="button" class="xa-btn xa-btn-sm" data-type-edit="{{ json_encode($t->only(['id', 'code', 'name', 'color', 'allowance_source', 'days_per_year', 'carry_forward_max', 'is_paid', 'allow_negative', 'requires_reason', 'is_active'])) }}">Edit</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Balances --}}
        <div class="xa-card mb-3">
            <div class="xa-card-head">
                <h2 class="xa-card-title">Balances · {{ $yearLabel }}</h2>
                <div class="xa-actions">
                    <div class="xa-search"><iconify-icon icon="solar:magnifer-linear"></iconify-icon><input type="search" id="balQ" class="xa-input" placeholder="Search people" style="width:200px"></div>
                    <select class="xa-select" style="width:auto" onchange="location.href='{{ route('leaves.policies') }}?year='+this.value" aria-label="Leave year">
                        @for ($y = $currentYear + 1; $y >= $currentYear - 3; $y--)
                            <option value="{{ $y }}" @selected($y == $year)>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="xa-table-wrap" style="max-height:520px">
                <table class="xa-table" id="balTable">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            @foreach ($limitedTypes as $t)
                                <th class="num" title="Remaining / entitlement (used, pending)">{{ $t->name }}</th>
                            @endforeach
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($people->where('track_attendance', true) as $p)
                            <tr data-search="{{ strtolower($p->name . ' ' . $p->email) }}">
                                <td><span class="xa-person-name">{{ $p->name }}</span></td>
                                @foreach ($balances[$p->id] ?? [] as $b)
                                    <td class="num">
                                        <strong style="{{ $b['remaining'] < 0 ? 'color:#b42318' : '' }}">{{ $n($b['remaining']) }}</strong><span class="xa-muted"> / {{ $n($b['entitlement']) }}</span>
                                        <div class="xa-muted" style="font-size:.72rem">{{ $n($b['used']) }} used{{ $b['pending'] ? ', ' . $n($b['pending']) . ' pending' : '' }}{{ ($b['carried'] || $b['adjustment']) ? ' · ' . ($b['carried'] ? '+' . $n($b['carried']) . ' cf ' : '') . ($b['adjustment'] ? ($b['adjustment'] > 0 ? '+' : '') . $n($b['adjustment']) . ' adj' : '') : '' }}</div>
                                    </td>
                                @endforeach
                                <td class="num"><button type="button" class="xa-btn xa-btn-sm" data-adjust="{{ $p->id }}" data-name="{{ $p->name }}">Adjust</button></td>
                            </tr>
                        @empty
                            <tr><td colspan="{{ $limitedTypes->count() + 2 }}"><div class="xa-empty">No tracked employees.</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="xa-card">
            <div class="xa-card-head"><h2 class="xa-card-title">Recent balance adjustments</h2></div>
            <div class="xa-table-wrap">
                <table class="xa-table">
                    <thead><tr><th>When</th><th>Employee</th><th>Type</th><th class="num">Year</th><th class="num">Days</th><th>Reason</th><th>By</th></tr></thead>
                    <tbody>
                        @forelse ($recentAdjustments as $a)
                            <tr>
                                <td style="white-space:nowrap">{{ $a->created_at?->format('d M Y, h:i A') }}</td>
                                <td>{{ $a->balance?->user?->name }}</td>
                                <td>{{ $a->balance?->type?->name }}</td>
                                <td class="num">{{ $a->balance?->year }}</td>
                                <td class="num"><strong>{{ $a->days > 0 ? '+' : '' }}{{ $n($a->days) }}</strong></td>
                                <td>{{ $a->reason }}</td>
                                <td>{{ $a->admin?->name }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7"><div class="xa-muted py-2">No adjustments yet.</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Type modal --}}
    <div class="modal fade xa-modal xa" id="xaType" tabindex="-1" aria-labelledby="xaTypeTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" id="xaTypeForm" novalidate>
                <div class="modal-header"><h5 class="modal-title" id="xaTypeTitle">Leave type</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                <div class="modal-body xa-stack">
                    <div class="xa-form-row">
                        <div class="xa-field"><label for="tName">Name</label><input id="tName" name="name" class="xa-input" maxlength="60" required></div>
                        <div class="xa-field"><label for="tColor">Colour</label><input id="tColor" name="color" type="color" class="xa-input" style="padding:3px" value="#4f7a06"></div>
                    </div>
                    <div class="xa-field">
                        <label for="tSource">Allowance</label>
                        <select id="tSource" name="allowance_source" class="xa-select">
                            @foreach (\App\Models\LeaveType::SOURCES as $k => $v)<option value="{{ $k }}">{{ $v }}</option>@endforeach
                        </select>
                    </div>
                    <div class="xa-form-row" data-limited>
                        <div class="xa-field" data-fixed><label for="tDays">Days per year</label><input id="tDays" name="days_per_year" type="number" class="xa-input" min="0" max="365" step="0.5"></div>
                        <div class="xa-field"><label for="tCarry">Max carry forward</label><input id="tCarry" name="carry_forward_max" type="number" class="xa-input" min="0" max="365" step="0.5" value="0"><div class="xa-help">0 = full reset each year.</div></div>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <label class="d-flex align-items-center gap-2 m-0"><span class="xa-switch"><input type="checkbox" name="is_paid" value="1"><span></span></span> Paid leave</label>
                        <label class="d-flex align-items-center gap-2 m-0" data-limited><span class="xa-switch"><input type="checkbox" name="allow_negative" value="1"><span></span></span> Allow requests beyond the balance</label>
                        <label class="d-flex align-items-center gap-2 m-0"><span class="xa-switch"><input type="checkbox" name="requires_reason" value="1"><span></span></span> Reason required</label>
                        <label class="d-flex align-items-center gap-2 m-0"><span class="xa-switch"><input type="checkbox" name="is_active" value="1"><span></span></span> Active (employees can apply)</label>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="xa-btn" data-bs-dismiss="modal">Close</button><button type="submit" class="xa-btn xa-btn-primary">Save</button></div>
            </form>
        </div>
    </div>

    {{-- Adjust modal --}}
    <div class="modal fade xa-modal xa" id="xaAdjust" tabindex="-1" aria-labelledby="xaAdjustTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" id="xaAdjustForm" novalidate>
                <div class="modal-header"><div><h5 class="modal-title" id="xaAdjustTitle">Adjust balance</h5><div class="xa-sub" data-name></div></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                <div class="modal-body xa-stack">
                    <input type="hidden" name="user_id">
                    <input type="hidden" name="year" value="{{ $year }}">
                    <div class="xa-form-row">
                        <div class="xa-field">
                            <label for="adjType">Leave type</label>
                            <select id="adjType" name="leave_type_id" class="xa-select">
                                @foreach ($limitedTypes as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach
                            </select>
                        </div>
                        <div class="xa-field">
                            <label for="adjDays">Days (+ / −)</label>
                            <input id="adjDays" name="days" type="number" step="0.5" class="xa-input" placeholder="e.g. 2 or -1" required>
                        </div>
                    </div>
                    <div class="xa-field"><label for="adjReason">Reason</label><input id="adjReason" name="reason" class="xa-input" maxlength="255" placeholder="e.g. Worked on public holiday" required></div>
                    <div class="xa-help">Applies to leave year {{ $yearLabel }}. Logged with your name.</div>
                </div>
                <div class="modal-footer"><button type="button" class="xa-btn" data-bs-dismiss="modal">Close</button><button type="submit" class="xa-btn xa-btn-primary">Save adjustment</button></div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const urls = {
            settings: @json(route('leaves.policies.settings')),
            typeStore: @json(route('leaves.types.store')),
            typeUpdate: @json(route('leaves.types.update', ['type' => '__ID__'])),
            adjust: @json(route('leaves.balances.adjust')),
            tracking: @json(route('leaves.tracking', ['user' => '__ID__'])),
        };
        const reload = () => setTimeout(() => location.reload(), 600);

        async function submit(form, url, method, body) {
            const btn = form.querySelector('[type="submit"]');
            XA.showErrors(form, {});
            XA.busy(btn, true);
            try {
                const data = await XA.request(url, { method, body });
                XA.toast('success', data.success);
                return data;
            } catch (err) {
                XA.showErrors(form, err.errors);
                throw err;
            } finally {
                XA.busy(btn, false);
            }
        }

        const allowance = document.getElementById('xaAllowance');
        allowance.addEventListener('submit', (e) => {
            e.preventDefault();
            submit(allowance, urls.settings, 'POST', { annual_leave_allowance: allowance.annual_leave_allowance.value, leave_year_start_month: allowance.leave_year_start_month.value }).then(reload).catch(() => {});
        });

        // Types
        const typeModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('xaType'));
        const tf = document.getElementById('xaTypeForm');
        let editing = null;
        function syncSource() {
            const src = tf.allowance_source.value;
            tf.querySelectorAll('[data-limited]').forEach(n => n.hidden = src === 'none');
            tf.querySelector('[data-fixed]').hidden = src !== 'fixed';
        }
        tf.allowance_source.addEventListener('change', syncSource);
        document.querySelectorAll('[data-type-edit]').forEach(b => b.addEventListener('click', () => {
            const t = b.dataset.typeEdit === 'new' ? null : JSON.parse(b.dataset.typeEdit);
            editing = t;
            tf.reset();
            XA.showErrors(tf, {});
            document.getElementById('xaTypeTitle').textContent = t ? 'Edit ' + t.name : 'Add leave type';
            tf.name.value = t ? t.name : '';
            tf.color.value = t ? t.color : '#4f7a06';
            tf.allowance_source.value = t ? t.allowance_source : 'fixed';
            tf.allowance_source.querySelector('option[value="annual"]').disabled = false;
            if (t && t.code === 'annual') Array.from(tf.allowance_source.options).forEach(o => o.disabled = o.value !== 'annual');
            else Array.from(tf.allowance_source.options).forEach(o => o.disabled = false);
            tf.days_per_year.value = t && t.days_per_year !== null ? t.days_per_year : '';
            tf.carry_forward_max.value = t ? t.carry_forward_max : 0;
            tf.is_paid.checked = t ? !!t.is_paid : true;
            tf.allow_negative.checked = t ? !!t.allow_negative : false;
            tf.requires_reason.checked = t ? !!t.requires_reason : true;
            tf.is_active.checked = t ? !!t.is_active : true;
            syncSource();
            typeModal.show();
        }));
        tf.addEventListener('submit', (e) => {
            e.preventDefault();
            const body = {
                name: tf.name.value, color: tf.color.value, allowance_source: tf.allowance_source.value,
                days_per_year: tf.days_per_year.value || null, carry_forward_max: tf.carry_forward_max.value || 0,
                is_paid: tf.is_paid.checked, allow_negative: tf.allow_negative.checked, requires_reason: tf.requires_reason.checked, is_active: tf.is_active.checked,
            };
            submit(tf, editing ? urls.typeUpdate.replace('__ID__', editing.id) : urls.typeStore, editing ? 'PUT' : 'POST', body)
                .then(() => { typeModal.hide(); reload(); }).catch(() => {});
        });

        // Adjust
        const adjModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('xaAdjust'));
        const af = document.getElementById('xaAdjustForm');
        document.querySelectorAll('[data-adjust]').forEach(b => b.addEventListener('click', () => {
            af.reset();
            XA.showErrors(af, {});
            af.user_id.value = b.dataset.adjust;
            af.querySelector('[data-name]').textContent = b.dataset.name;
            adjModal.show();
        }));
        af.addEventListener('submit', (e) => {
            e.preventDefault();
            submit(af, urls.adjust, 'POST', Object.fromEntries(new FormData(af))).then(() => { adjModal.hide(); reload(); }).catch(() => {});
        });

        // Tracking
        document.querySelectorAll('[data-track]').forEach(cb => cb.addEventListener('change', async () => {
            try {
                const d = await XA.request(urls.tracking.replace('__ID__', cb.dataset.track), { body: { track_attendance: cb.checked } });
                XA.toast('success', d.success);
            } catch (e) { cb.checked = !cb.checked; }
        }));

        XA.liveFilter(document.getElementById('balQ'), () => document.querySelectorAll('#balTable tbody tr[data-search]'));
    });
    </script>
</x-app-layout>
