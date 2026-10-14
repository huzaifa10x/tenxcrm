{{-- Per-employee performance for a period. Needs: $rows, $from, $to; optional $compact. --}}
@php
    $__q = empty($periodQuery) ? ['from' => $from->toDateString(), 'to' => $to->toDateString()] : $periodQuery;
    $__pctClass = fn ($p) => $p === null ? '' : ($p >= 90 ? '' : ($p >= 75 ? 'is-warn' : 'is-bad'));
    $__compact = $compact ?? false;
@endphp
<div class="xa-table-wrap" @if ($__compact) style="max-height:520px" @endif>
    <table class="xa-table" data-perf-table>
        <thead>
            <tr>
                <th class="xa-sortable">Employee</th>
                <th class="xa-sortable num" title="Working days in the period (weekends and holidays excluded)">Work days</th>
                <th class="xa-sortable num">Present</th>
                <th class="xa-sortable num">Absent</th>
                <th class="xa-sortable num">Late</th>
                <th class="xa-sortable num" title="Total minutes late">Late min</th>
                <th class="xa-sortable num">Half days</th>
                <th class="xa-sortable num" title="Approved leave days">Leave</th>
                <th class="xa-sortable num" title="Average hours on days with a punch-in and punch-out">Avg hours</th>
                <th class="xa-sortable num" title="Missed punch-outs">Missed out</th>
                <th class="xa-sortable" title="(Present − ½ half days) ÷ (elapsed working days − leave)">Attendance</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $r)
                @php $s = $r['summary']; $u = $r['user']; @endphp
                <tr class="is-clickable" data-search="{{ strtolower($u->name . ' ' . $u->email . ' ' . $u->departmentNames()) }}"
                    data-dept="{{ $u->roles->pluck('id')->implode(',') }}"
                    onclick="location.href='{{ route('attendance.index', $__q + ['user_id' => $u->id]) }}'">
                    <td data-sort="{{ strtolower($u->name) }}">
                        <a class="xa-person" href="{{ route('attendance.index', $__q + ['user_id' => $u->id]) }}">
                            @include('attendance.partials.avatar', ['user' => $u])
                            <span style="min-width:0">
                                <span class="xa-person-name d-block">{{ $u->name }}</span>
                                <span class="xa-person-meta d-block">{{ $u->departmentNames() ?: '—' }}</span>
                            </span>
                        </a>
                    </td>
                    <td class="num" data-sort="{{ $s['working_days'] }}">{{ $s['working_days'] }}</td>
                    <td class="num" data-sort="{{ $s['present'] }}">{{ $s['present'] }}</td>
                    <td class="num" data-sort="{{ $s['absent'] }}">{!! $s['absent'] ? '<span class="xa-badge xa-badge-absent no-dot">' . $s['absent'] . '</span>' : '<span class="xa-dim">0</span>' !!}</td>
                    <td class="num" data-sort="{{ $s['late'] }}">{!! $s['late'] ? '<span class="xa-badge xa-badge-late no-dot">' . $s['late'] . '</span>' : '<span class="xa-dim">0</span>' !!}</td>
                    <td class="num" data-sort="{{ $s['late_minutes'] }}">{{ $s['late_minutes'] ?: '—' }}</td>
                    <td class="num" data-sort="{{ $s['half_days'] }}">{{ $s['half_days'] ?: '—' }}</td>
                    <td class="num" data-sort="{{ $s['leave_days'] }}">{{ $s['leave_days'] ?: '—' }}</td>
                    <td class="num" data-sort="{{ $s['avg_minutes'] ?? -1 }}">{{ $s['avg_minutes'] !== null ? number_format($s['avg_minutes'] / 60, 1) . 'h' : '—' }}</td>
                    <td class="num" data-sort="{{ $s['missed_punch_outs'] }}">{!! $s['missed_punch_outs'] ? '<span class="xa-flag xa-flag-missed">' . $s['missed_punch_outs'] . '</span>' : '<span class="xa-dim">0</span>' !!}</td>
                    <td data-sort="{{ $s['attendance_pct'] ?? -1 }}">
                        @if ($s['attendance_pct'] !== null)
                            <div class="xa-pct {{ $__pctClass($s['attendance_pct']) }}">
                                <div class="xa-pct-bar"><span style="width: {{ min(100, $s['attendance_pct']) }}%"></span></div>
                                <span class="xa-pct-val">{{ $s['attendance_pct'] }}%</span>
                            </div>
                        @else
                            <span class="xa-dim">No days yet</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr class="xa-no-sort"><td colspan="11"><div class="xa-empty"><iconify-icon icon="solar:users-group-rounded-linear"></iconify-icon>No employees match these filters.</div></td></tr>
            @endforelse
            <tr class="xa-no-sort" data-perf-empty hidden><td colspan="11"><div class="xa-empty">No employees match your search.</div></td></tr>
        </tbody>
    </table>
</div>
