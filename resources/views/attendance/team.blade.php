<x-app-layout>
    <x-slot name="header">Team attendance</x-slot>
    @include('attendance.partials.assets')

    @php
        $T = $totals;
        $isMonth = empty($filters['from']);
        $monthC = \Carbon\Carbon::parse($month . '-01');
        $periodQuery = $isMonth ? ['month' => $month] : ['from' => $from->toDateString(), 'to' => $to->toDateString()];
        $keep = array_filter(['department' => $filters['department'] ?? null, 'q' => $filters['q'] ?? null, 'status' => $filters['status'] ?? null]);
        $boardCols = [
            'in' => ['On time', 'present'],
            'late' => ['Late / half day', 'late'],
            'out' => ['Punched out', 'early_out'],
            'leave' => ['On leave', 'leave'],
            'missing' => ['Not in yet', 'absent'],
        ];
    @endphp

    <div class="xa">
        @include('attendance.partials.tabs', ['active' => 'team'])

        <div class="xa-head">
            <div>
                <h1 class="xa-title">Team attendance</h1>
                <div class="xa-sub">
                    {{ $isMonth ? $monthC->format('F Y') : $from->format('d M Y') . ' – ' . $to->format('d M Y') }}
                    · {{ $T['working_days'] }} working days (weekends &amp; holidays excluded)
                </div>
            </div>
            <div class="xa-actions">
                <a class="xa-btn xa-btn-sm" href="{{ route('attendance.index', $keep + ['month' => $monthC->copy()->subMonth()->format('Y-m')]) }}" aria-label="Previous month">&lsaquo;</a>
                <input type="month" class="xa-input" style="width:auto" value="{{ $month }}" aria-label="Month"
                    onchange="if(this.value){const u=new URL('{{ route('attendance.index', $keep) }}');u.searchParams.set('month',this.value);location.href=u}">
                <a class="xa-btn xa-btn-sm" href="{{ route('attendance.index', $keep + ['month' => $monthC->copy()->addMonth()->format('Y-m')]) }}" aria-label="Next month">&rsaquo;</a>
                <a class="xa-btn xa-btn-sm" href="{{ route('attendance.export', $periodQuery + $keep) }}"><iconify-icon icon="solar:download-linear"></iconify-icon> CSV</a>
                <a class="xa-btn xa-btn-sm" href="{{ route('attendance.monthly', ['view' => 'classic', 'month' => $month]) }}" title="The previous month grid">Classic grid</a>
                <button type="button" class="xa-btn xa-btn-dark" data-add-record><iconify-icon icon="solar:add-circle-linear"></iconify-icon> Add record</button>
            </div>
        </div>

        @if ($pendingLeaves)
            <div class="xa-callout is-warn mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span><strong>{{ $pendingLeaves }}</strong> leave {{ Str::plural('request', $pendingLeaves) }} waiting for review.</span>
                <a href="{{ route('leaves.admin') }}" class="xa-btn xa-btn-sm">Review now</a>
            </div>
        @endif

        <div class="xa-stats">
            <div class="xa-stat is-good"><div class="xa-stat-label">Team attendance</div><div class="xa-stat-value">{{ $T['attendance_pct'] !== null ? $T['attendance_pct'] . '%' : '—' }}</div><div class="xa-stat-hint">Average across {{ $T['employees'] }} people</div></div>
            <div class="xa-stat is-good"><div class="xa-stat-label">Present days</div><div class="xa-stat-value">{{ $T['present'] }}</div><div class="xa-stat-hint">Incl. {{ $T['half_days'] }} half days</div></div>
            <div class="xa-stat {{ $T['absent'] ? 'is-bad' : 'is-muted' }}"><div class="xa-stat-label">Absences</div><div class="xa-stat-value">{{ $T['absent'] }}</div><div class="xa-stat-hint">Without approved leave</div></div>
            <div class="xa-stat {{ $T['late'] ? 'is-warn' : 'is-muted' }}"><div class="xa-stat-label">Late arrivals</div><div class="xa-stat-value">{{ $T['late'] }}</div><div class="xa-stat-hint">{{ \App\Services\Attendance\AttendancePolicy::formatMinutes($T['late_minutes']) }} in total</div></div>
            <div class="xa-stat is-info"><div class="xa-stat-label">Leave days</div><div class="xa-stat-value">{{ $T['leave_days'] }}</div><div class="xa-stat-hint">Approved</div></div>
            <div class="xa-stat {{ $T['missed_punch_outs'] ? 'is-bad' : 'is-muted' }}"><div class="xa-stat-label">Missed punch-outs</div><div class="xa-stat-value">{{ $T['missed_punch_outs'] }}</div><div class="xa-stat-hint">Need a correction?</div></div>
            <div class="xa-stat is-good"><div class="xa-stat-label">Avg hours / day</div><div class="xa-stat-value">{{ $T['avg_minutes'] !== null ? number_format($T['avg_minutes'] / 60, 1) . 'h' : '—' }}</div><div class="xa-stat-hint">Scheduled {{ number_format($policy->scheduledMinutes() / 60, 1) }}h</div></div>
        </div>

        <div class="xa-grid xa-grid-main mb-3">
            <div class="xa-card">
                <div class="xa-card-head">
                    <h2 class="xa-card-title">Today · {{ now()->format('l d M') }}</h2>
                    @unless ($todayBoard['working'])<span class="xa-badge xa-badge-weekend">Not a working day</span>@endunless
                </div>
                <div class="xa-card-body">
                    <div class="xa-board">
                        @foreach ($boardCols as $key => [$label, $badge])
                            <div class="xa-board-col">
                                <div class="xa-board-head"><span>{{ $label }}</span><span class="xa-badge xa-badge-{{ $badge }} no-dot">{{ count($todayBoard['groups'][$key]) }}</span></div>
                                <div class="xa-board-list">
                                    @forelse ($todayBoard['groups'][$key] as $item)
                                        <a class="xa-chip" href="{{ route('attendance.index', ['user_id' => $item['user']->id]) }}">
                                            <span>{{ $item['user']->name }}</span><span>{{ $item['note'] }}</span>
                                        </a>
                                    @empty
                                        <div class="xa-dim" style="font-size:.76rem">—</div>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="xa-card">
                <div class="xa-card-head"><h2 class="xa-card-title">Daily trend</h2></div>
                <div class="xa-card-body"><div id="xaTeamTrend" class="xa-chart"></div></div>
            </div>
        </div>

        <div class="xa-card">
            <div class="xa-card-head">
                <h2 class="xa-card-title">Employee performance</h2>
                <span class="xa-card-note">Click a person for their day-by-day history</span>
            </div>
            <form class="xa-filters" method="GET" action="{{ route('attendance.index') }}">
                <div class="xa-field" style="flex:1;min-width:180px">
                    <label for="tQ">Search</label>
                    <div class="xa-search">
                        <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                        <input type="search" id="tQ" name="q" class="xa-input" value="{{ $filters['q'] ?? '' }}" placeholder="Name, email or department" autocomplete="off">
                    </div>
                </div>
                <div class="xa-field">
                    <label for="tDept">Department</label>
                    <select id="tDept" name="department" class="xa-select">
                        <option value="">All departments</option>
                        @foreach ($departments as $d)
                            <option value="{{ $d->id }}" @selected(($filters['department'] ?? '') == $d->id)>{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="xa-field">
                    <label for="tEmp">Employee</label>
                    <select id="tEmp" name="user_id" class="xa-select">
                        <option value="">Everyone</option>
                        @foreach ($employees as $e)
                            <option value="{{ $e->id }}">{{ $e->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="xa-field">
                    <label for="tFrom">From</label>
                    <input type="date" id="tFrom" name="from" class="xa-input" value="{{ $from->toDateString() }}">
                </div>
                <div class="xa-field">
                    <label for="tTo">To</label>
                    <input type="date" id="tTo" name="to" class="xa-input" value="{{ $to->toDateString() }}">
                </div>
                <div class="xa-field">
                    <label for="tStatus">Show</label>
                    <select id="tStatus" name="status" class="xa-select">
                        <option value="">Everyone</option>
                        <option value="issues" @selected(($filters['status'] ?? '') === 'issues')>Only with issues</option>
                    </select>
                </div>
                <button class="xa-btn xa-btn-dark" type="submit">Apply</button>
                @if (array_filter($filters))
                    <a class="xa-btn" href="{{ route('attendance.index') }}">Reset</a>
                @endif
            </form>
            @include('attendance.partials.performance-table', ['rows' => $rows, 'periodQuery' => $periodQuery])
        </div>
    </div>

    @include('attendance.partials.record-modal')

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const table = document.querySelector('[data-perf-table]');
        XA.sortable(table);
        XA.liveFilter(document.getElementById('tQ'), () => table.querySelectorAll('tbody tr[data-search]'), table.querySelector('[data-perf-empty]'));

        const trend = @json($trend);
        if (window.ApexCharts && trend.length) {
            new ApexCharts(document.getElementById('xaTeamTrend'), {
                chart: { type: 'bar', height: 250, stacked: true, toolbar: { show: false }, fontFamily: 'inherit' },
                series: [
                    { name: 'On time', data: trend.map(d => d.present) },
                    { name: 'Late', data: trend.map(d => d.late) },
                    { name: 'Leave', data: trend.map(d => d.leave) },
                    { name: 'Absent', data: trend.map(d => d.absent) },
                ],
                xaxis: { categories: trend.map(d => d.label), labels: { rotate: -45, style: { fontSize: '10px' } } },
                colors: ['#a9e324', '#f0a400', '#3b82f6', '#e5484d'],
                plotOptions: { bar: { borderRadius: 3, columnWidth: '60%' } },
                dataLabels: { enabled: false },
                legend: { position: 'top', horizontalAlign: 'left' },
                grid: { borderColor: '#eef1ed' },
                yaxis: { labels: { formatter: v => Math.round(v) } },
            }).render();
        } else if (document.getElementById('xaTeamTrend')) {
            document.getElementById('xaTeamTrend').innerHTML = '<div class="xa-empty">No working days yet in this period.</div>';
        }
    });
    </script>
</x-app-layout>
