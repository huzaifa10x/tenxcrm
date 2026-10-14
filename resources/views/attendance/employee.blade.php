<x-app-layout>
    <x-slot name="header">{{ $isSelf ? 'My attendance' : 'Attendance · ' . $employee->name }}</x-slot>
    @include('attendance.partials.assets')

    @php
        $s = $summary;
        $fmtT = fn ($t) => $t ? \Carbon\Carbon::parse($t)->format('h:i A') : null;
        $isMonth = empty($filters['from']);
        $monthC = \Carbon\Carbon::parse($month . '-01');
        $baseQuery = array_filter(['user_id' => $canManage ? $employee->id : null]);
        $pctClass = fn ($p) => $p === null ? '' : ($p >= 90 ? '' : ($p >= 75 ? 'is-warn' : 'is-bad'));

        // Today
        $t = $today;
        $todayStatus = $todayLeave && !$t?->punch_in ? 'leave'
            : ($t?->punch_in ? ($t->punch_out ? ($t->status ?: 'present') : 'working')
            : (!$policy->isWorkingDay(now()) ? ($policy->isHoliday(now()) ? 'holiday' : 'weekend') : 'pending'));
        $todayIn = $t?->punch_in ? $policy->at($t->dateString(), $t->punch_in) : null;
        $todayWorked = $t?->punch_out ? $policy->workedMinutes($t) : null;
    @endphp

    <div class="xa">
        @include('attendance.partials.tabs', ['active' => $isSelf ? 'my-attendance' : 'team'])

        <div class="xa-head">
            <div class="xa-head-title">
                @if (!$isSelf)
                    <a href="{{ route('attendance.index', $isMonth ? ['month' => $month] : ['from' => $from->toDateString(), 'to' => $to->toDateString()]) }}" class="xa-btn xa-btn-sm" aria-label="Back to team">
                        <iconify-icon icon="solar:arrow-left-linear"></iconify-icon> Team
                    </a>
                @endif
                <div>
                    <h1 class="xa-title">{{ $isSelf ? 'My attendance' : $employee->name }}</h1>
                    <div class="xa-sub">
                        {{ $isMonth ? $monthC->format('F Y') : $from->format('d M Y') . ' – ' . $to->format('d M Y') }}
                        · Office {{ \Carbon\Carbon::parse($policy->open)->format('h:i A') }} – {{ \Carbon\Carbon::parse($policy->close)->format('h:i A') }}, {{ $policy->graceMinutes }} min grace
                    </div>
                </div>
            </div>
            <div class="xa-actions">
                @if ($canManage && $employees->count())
                    <select class="xa-select" style="width:auto;min-width:180px" aria-label="Switch employee"
                        onchange="location.href='{{ route('attendance.index') }}?{{ $isMonth ? 'month=' . $month : 'from=' . $from->toDateString() . '&to=' . $to->toDateString() }}&user_id='+this.value">
                        @foreach ($employees as $e)
                            <option value="{{ $e->id }}" @selected($e->id == $employee->id)>{{ $e->name }}</option>
                        @endforeach
                    </select>
                @endif
                <a class="xa-btn xa-btn-sm" href="{{ route('attendance.index', $baseQuery + ['month' => $monthC->copy()->subMonth()->format('Y-m')]) }}" aria-label="Previous month">&lsaquo;</a>
                <input type="month" class="xa-input" style="width:auto" value="{{ $month }}" max="{{ now()->format('Y-m') }}" aria-label="Month"
                    onchange="if(this.value) location.href='{{ route('attendance.index', $baseQuery) }}{{ $baseQuery ? '&' : '?' }}month='+this.value">
                <a class="xa-btn xa-btn-sm" href="{{ route('attendance.index', $baseQuery + ['month' => $monthC->copy()->addMonth()->format('Y-m')]) }}" aria-label="Next month">&rsaquo;</a>
                <a class="xa-btn xa-btn-sm" href="{{ route('attendance.export', ['user_id' => $employee->id] + ($isMonth ? ['month' => $month] : ['from' => $from->toDateString(), 'to' => $to->toDateString()])) }}">
                    <iconify-icon icon="solar:download-linear"></iconify-icon> CSV
                </a>
                @if ($isSelf)
                    <a class="xa-btn xa-btn-primary" href="{{ route('leaves.index') }}#apply"><iconify-icon icon="solar:calendar-add-linear"></iconify-icon> Apply for leave</a>
                @elseif ($canManage)
                    <button type="button" class="xa-btn xa-btn-dark" data-add-record data-user="{{ $employee->id }}"><iconify-icon icon="solar:add-circle-linear"></iconify-icon> Add record</button>
                @endif
            </div>
        </div>

        {{-- Today --}}
        <section class="xa-hero" aria-label="Today">
            <div class="xa-person">
                @include('attendance.partials.avatar', ['user' => $employee, 'class' => 'xa-avatar-lg'])
                <div style="min-width:0">
                    <div class="xa-person-name" style="white-space:normal">{{ $employee->name }}</div>
                    <div class="xa-person-meta">{{ $employee->departmentNames() ?: 'No department' }}</div>
                    <div class="mt-2">
                        @include('attendance.partials.badge', ['status' => $todayStatus])
                        @if ($todayLeave) <span class="xa-badge xa-badge-leave no-dot">{{ $todayLeave->type?->name }}</span> @endif
                        @if ($t && $t->dateString() !== now()->toDateString()) <span class="xa-flag xa-flag-late">Shift from {{ \Carbon\Carbon::parse($t->dateString())->format('d M') }}</span> @endif
                    </div>
                </div>
            </div>
            <div>
                <div class="xa-hero-k">Punch in</div>
                <div class="xa-hero-v">{{ $todayIn ? $todayIn->format('h:i A') : '—' }}</div>
                @if ($t && $t->punch_in && $policy->lateMinutes($t) > 0)
                    <div class="xa-hero-v"><small>{{ $policy->lateMinutes($t) }} min late</small></div>
                @endif
            </div>
            <div>
                <div class="xa-hero-k">Punch out</div>
                <div class="xa-hero-v">{{ $fmtT($t?->punch_out) ?? '—' }}</div>
            </div>
            <div>
                <div class="xa-hero-k">Worked today</div>
                <div class="xa-hero-v" @if ($todayStatus === 'working' && $todayIn) data-since="{{ $todayIn->toIso8601String() }}" @endif>
                    {{ $todayWorked !== null ? \App\Services\Attendance\AttendancePolicy::formatMinutes($todayWorked) : ($todayIn ? \App\Services\Attendance\AttendancePolicy::formatMinutes((int) $todayIn->diffInMinutes(now())) : '—') }}
                </div>
            </div>
            <div>
                <div class="xa-hero-k">This period</div>
                <div class="xa-hero-v">{{ $s['attendance_pct'] !== null ? $s['attendance_pct'] . '%' : '—' }} <small>attendance</small></div>
            </div>
        </section>

        {{-- Summary --}}
        <div class="xa-stats">
            <div class="xa-stat is-good"><div class="xa-stat-label">Present</div><div class="xa-stat-value">{{ $s['present'] }} <small>/ {{ $s['elapsed_days'] }} days</small></div><div class="xa-stat-hint">{{ $s['working_days'] }} working days in period</div></div>
            <div class="xa-stat {{ $s['absent'] ? 'is-bad' : 'is-muted' }}"><div class="xa-stat-label">Absent</div><div class="xa-stat-value">{{ $s['absent'] }}</div><div class="xa-stat-hint">No punch, no approved leave</div></div>
            <div class="xa-stat {{ $s['late'] ? 'is-warn' : 'is-muted' }}"><div class="xa-stat-label">Late arrivals</div><div class="xa-stat-value">{{ $s['late'] }}</div><div class="xa-stat-hint">{{ \App\Services\Attendance\AttendancePolicy::formatMinutes($s['late_minutes']) }} late in total</div></div>
            <div class="xa-stat {{ $s['half_days'] ? 'is-warn' : 'is-muted' }}"><div class="xa-stat-label">Half days</div><div class="xa-stat-value">{{ $s['half_days'] }}</div><div class="xa-stat-hint">In after {{ \Carbon\Carbon::parse($policy->halfDayAfter)->format('h:i A') }}</div></div>
            <div class="xa-stat {{ $s['early_outs'] ? 'is-warn' : 'is-muted' }}"><div class="xa-stat-label">Early departures</div><div class="xa-stat-value">{{ $s['early_outs'] }}</div><div class="xa-stat-hint">{{ $s['early_minutes'] }} min before {{ \Carbon\Carbon::parse($policy->earlyOutBefore)->format('h:i A') }}</div></div>
            <div class="xa-stat {{ $s['missed_punch_outs'] ? 'is-bad' : 'is-muted' }}"><div class="xa-stat-label">Missed punch-outs</div><div class="xa-stat-value">{{ $s['missed_punch_outs'] }}</div><div class="xa-stat-hint">Closed automatically</div></div>
            <div class="xa-stat is-info"><div class="xa-stat-label">On leave</div><div class="xa-stat-value">{{ $s['leave_days'] }}</div><div class="xa-stat-hint">Approved leave days</div></div>
            <div class="xa-stat is-muted"><div class="xa-stat-label">Holidays</div><div class="xa-stat-value">{{ $s['holidays'] }}</div><div class="xa-stat-hint">Public holidays</div></div>
            <div class="xa-stat is-good"><div class="xa-stat-label">Avg hours / day</div><div class="xa-stat-value">{{ $s['avg_minutes'] !== null ? \App\Services\Attendance\AttendancePolicy::formatMinutes($s['avg_minutes']) : '—' }}</div><div class="xa-stat-hint">{{ \App\Services\Attendance\AttendancePolicy::formatMinutes($s['worked_minutes']) }} total</div></div>
        </div>

        <div class="xa-grid xa-grid-main mb-3">
            {{-- Calendar --}}
            <div class="xa-card">
                <div class="xa-card-head">
                    <h2 class="xa-card-title">{{ $isMonth ? $monthC->format('F Y') : 'Calendar' }}</h2>
                    <span class="xa-card-note">Click a day for details</span>
                </div>
                <div class="xa-card-body">
                    @if ($isMonth)
                        <div class="xa-cal" role="grid" aria-label="Attendance calendar">
                            @foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $dow)
                                <div class="xa-cal-dow">{{ $dow }}</div>
                            @endforeach
                            @for ($i = 1; $i < $monthC->dayOfWeekIso; $i++)
                                <div class="xa-cal-day is-empty"></div>
                            @endfor
                            @foreach ($calendar as $d)
                                @php
                                    $dc = \Carbon\Carbon::parse($d['date']);
                                    $label = $d['punch_in'] ? \Carbon\Carbon::parse($d['punch_in'])->format('g:i') . ($d['punch_out'] ? '–' . \Carbon\Carbon::parse($d['punch_out'])->format('g:i') : '') : (in_array($d['status'], ['upcoming', 'not_joined', 'pending']) ? '' : \App\Services\Attendance\AttendanceReport::statusLabel($d['status']));
                                    $title = $dc->format('D d M') . ' · ' . \App\Services\Attendance\AttendanceReport::statusLabel($d['status']) . ($d['leave'] ? ' (' . $d['leave'] . ')' : '') . ($d['late_minutes'] ? ' · ' . $d['late_minutes'] . ' min late' : '') . ($d['missed_out'] ? ' · missed punch-out' : '');
                                @endphp
                                <div class="xa-cal-day s-{{ $d['status'] }} {{ $dc->isToday() ? 'is-today' : '' }} {{ $d['missed_out'] ? 'has-missed' : '' }} {{ $d['id'] ? 'is-link' : '' }}"
                                    @if ($d['id']) data-record="{{ $d['id'] }}" tabindex="0" @endif title="{{ $title }}" aria-label="{{ $title }}">
                                    <b>{{ $dc->day }}</b>
                                    <i>{{ $label }}</i>
                                </div>
                            @endforeach
                        </div>
                        <div class="xa-legend">
                            <span style="--c:#dff0b0">Present</span><span style="--c:#ffe3a3">Late</span><span style="--c:#ffd3a8">Half day</span>
                            <span style="--c:#dccdff">Early out</span><span style="--c:#f9c6c3">Absent</span><span style="--c:#c4dafc">Leave</span>
                            <span style="--c:#e5cdfc">Holiday</span><span style="--c:#e5484d">Missed punch-out</span>
                        </div>
                    @else
                        <div class="xa-muted">The calendar shows one month at a time — pick a month above. The history below covers your custom range.</div>
                    @endif
                </div>
            </div>

            {{-- Trend --}}
            <div class="xa-card">
                <div class="xa-card-head">
                    <h2 class="xa-card-title">6-month trend</h2>
                </div>
                <div class="xa-card-body">
                    <div id="xaTrend" class="xa-chart"></div>
                    <div class="xa-table-wrap mt-2">
                        <table class="xa-table">
                            <thead><tr><th>Month</th><th class="num">Att.</th><th class="num">Late</th><th class="num">Absent</th><th class="num">Avg h</th></tr></thead>
                            <tbody>
                                @foreach (array_reverse($trend) as $m)
                                    <tr><td>{{ $m['label'] }}</td><td class="num">{{ $m['pct'] !== null ? $m['pct'] . '%' : '—' }}</td><td class="num">{{ $m['late'] }}</td><td class="num">{{ $m['absent'] }}</td><td class="num">{{ $m['avg'] ?: '—' }}</td></tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- History --}}
        <div class="xa-card">
            <div class="xa-card-head">
                <h2 class="xa-card-title">History</h2>
                <span class="xa-card-note">{{ $days->count() }} {{ Str::plural('day', $days->count()) }}</span>
            </div>
            <form class="xa-filters" method="GET" action="{{ route('attendance.index') }}">
                @if ($canManage)<input type="hidden" name="user_id" value="{{ $employee->id }}">@endif
                <div class="xa-field">
                    <label for="fFrom">From</label>
                    <input type="date" id="fFrom" name="from" class="xa-input" value="{{ $from->toDateString() }}">
                </div>
                <div class="xa-field">
                    <label for="fTo">To</label>
                    <input type="date" id="fTo" name="to" class="xa-input" value="{{ $to->toDateString() }}">
                </div>
                <div class="xa-field">
                    <label for="fStatus">Status</label>
                    <select id="fStatus" name="status" class="xa-select">
                        <option value="">All days</option>
                        @foreach (['present' => 'Present', 'late' => 'Late', 'half_day' => 'Half day', 'early_out' => 'Early out', 'absent' => 'Absent', 'leave' => 'On leave', 'missed' => 'Missed punch-out', 'holiday' => 'Holiday', 'weekend' => 'Weekend'] as $k => $v)
                            <option value="{{ $k }}" @selected(($filters['status'] ?? '') === $k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="xa-field" style="flex:1;min-width:160px">
                    <label for="fQ">Search</label>
                    <div class="xa-search">
                        <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                        <input type="search" id="fQ" name="q" class="xa-input" value="{{ $filters['q'] ?? '' }}" placeholder="Date or weekday, e.g. Mon or 2026-10">
                    </div>
                </div>
                <button class="xa-btn xa-btn-dark" type="submit">Apply</button>
                @if (array_filter($filters))
                    <a class="xa-btn" href="{{ route('attendance.index', $baseQuery) }}">Reset</a>
                @endif
            </form>
            <div class="xa-table-wrap">
                <table class="xa-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Status</th>
                            <th>In</th>
                            <th>Out</th>
                            <th class="num">Worked</th>
                            <th class="num">Late</th>
                            <th class="num xa-hide-sm">Early out</th>
                            <th class="xa-hide-sm">Flags</th>
                            <th class="center">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($days as $d)
                            @php $dc = \Carbon\Carbon::parse($d['date']); @endphp
                            <tr class="{{ in_array($d['status'], ['weekend', 'upcoming', 'not_joined']) ? 'is-muted' : '' }} {{ $d['id'] ? 'is-clickable' : '' }}" @if ($d['id']) data-record="{{ $d['id'] }}" @endif>
                                <td>
                                    <div class="fw-semibold">{{ $dc->format('d M Y') }}</div>
                                    <div class="xa-muted" style="font-size:.74rem">{{ $dc->format('l') }}</div>
                                </td>
                                <td>
                                    @include('attendance.partials.badge', ['status' => $d['status'], 'class' => $d['status'] === 'pending' ? 'is-day' : ''])
                                    @if ($d['leave']) <div class="xa-muted" style="font-size:.72rem;margin-top:2px">{{ $d['leave'] }}</div> @endif
                                </td>
                                <td class="xa-time">{{ $fmtT($d['punch_in']) ?? '—' }}</td>
                                <td class="xa-time">{{ $fmtT($d['punch_out']) ?? '—' }}</td>
                                <td class="num">{{ $d['worked'] !== null && !$d['missed_out'] ? \App\Services\Attendance\AttendancePolicy::formatMinutes($d['worked']) : '—' }}</td>
                                <td class="num">{!! $d['late_minutes'] ? '<span class="xa-flag xa-flag-late">' . $d['late_minutes'] . ' min</span>' : '<span class="xa-dim">—</span>' !!}</td>
                                <td class="num xa-hide-sm">{!! $d['early_minutes'] ? '<span class="xa-flag xa-flag-early">' . $d['early_minutes'] . ' min</span>' : '<span class="xa-dim">—</span>' !!}</td>
                                <td class="xa-hide-sm">
                                    @if ($d['missed_out']) <span class="xa-flag xa-flag-missed">Missed punch-out</span> @endif
                                    @if ($d['edited']) <span class="xa-flag xa-flag-edited">Edited</span> @endif
                                </td>
                                <td class="center">
                                    @if ($d['notes']) <span class="xa-count">{{ $d['notes'] }}</span>
                                    @elseif ($canManage && !$d['id'] && in_array($d['status'], ['absent', 'pending']))
                                        <button type="button" class="xa-btn xa-btn-sm" data-add-record data-user="{{ $employee->id }}" data-date="{{ $d['date'] }}">Add</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9"><div class="xa-empty"><iconify-icon icon="solar:calendar-search-linear"></iconify-icon>No days match these filters.</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('attendance.partials.record-modal')

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const trend = @json($trend);
        if (window.ApexCharts && document.getElementById('xaTrend')) {
            new ApexCharts(document.getElementById('xaTrend'), {
                chart: { type: 'line', height: 240, toolbar: { show: false }, fontFamily: 'inherit' },
                series: [
                    { name: 'Attendance %', type: 'column', data: trend.map(m => m.pct ?? 0) },
                    { name: 'Avg hours', type: 'line', data: trend.map(m => m.avg ?? 0) },
                ],
                labels: trend.map(m => m.label),
                colors: ['#a9e324', '#0f1412'],
                stroke: { width: [0, 3], curve: 'smooth' },
                plotOptions: { bar: { borderRadius: 5, columnWidth: '45%' } },
                dataLabels: { enabled: false },
                yaxis: [
                    { seriesName: 'Attendance %', max: 100, min: 0, tickAmount: 4, labels: { formatter: v => Math.round(v) + '%' } },
                    { seriesName: 'Avg hours', opposite: true, min: 0, max: 12, tickAmount: 4, labels: { formatter: v => Math.round(v) + 'h' } },
                ],
                tooltip: { y: { formatter: (v, o) => o.seriesIndex === 0 ? v + '%' : v + 'h' } },
                legend: { position: 'top', horizontalAlign: 'left' },
                grid: { borderColor: '#eef1ed' },
            }).render();
        }

        // Live "worked today" counter.
        const live = document.querySelector('[data-since]');
        if (live) {
            const since = Date.parse(live.dataset.since);
            const tick = () => {
                const m = Math.max(0, Math.floor((Date.now() - since) / 60000));
                live.textContent = Math.floor(m / 60) + 'h ' + String(m % 60).padStart(2, '0') + 'm';
            };
            tick();
            setInterval(tick, 30000);
        }

        document.querySelectorAll('.xa-cal-day[data-record]').forEach(el => el.addEventListener('keydown', e => {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); XARecord.open(el.dataset.record); }
        }));
    });
    </script>
</x-app-layout>
