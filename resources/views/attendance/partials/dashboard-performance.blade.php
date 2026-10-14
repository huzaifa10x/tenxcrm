{{-- Fragment loaded into the admin dashboard (AJAX). --}}
@php $T = $totals; @endphp
<div class="xa">
    <div class="xa-sub mb-2">{{ $from->format('d M Y') }} – {{ $to->format('d M Y') }} · {{ $T['working_days'] }} working days (weekends &amp; holidays excluded)</div>
    <div class="xa-stats">
        <div class="xa-stat is-good"><div class="xa-stat-label">Attendance</div><div class="xa-stat-value">{{ $T['attendance_pct'] !== null ? $T['attendance_pct'] . '%' : '—' }}</div><div class="xa-stat-hint">{{ $T['employees'] }} employees</div></div>
        <div class="xa-stat {{ $T['absent'] ? 'is-bad' : 'is-muted' }}"><div class="xa-stat-label">Absences</div><div class="xa-stat-value">{{ $T['absent'] }}</div></div>
        <div class="xa-stat {{ $T['late'] ? 'is-warn' : 'is-muted' }}"><div class="xa-stat-label">Late</div><div class="xa-stat-value">{{ $T['late'] }} <small>· {{ $T['late_minutes'] }} min</small></div></div>
        <div class="xa-stat {{ $T['half_days'] ? 'is-warn' : 'is-muted' }}"><div class="xa-stat-label">Half days</div><div class="xa-stat-value">{{ $T['half_days'] }}</div></div>
        <div class="xa-stat is-info"><div class="xa-stat-label">Leave days</div><div class="xa-stat-value">{{ $T['leave_days'] }}</div></div>
        <div class="xa-stat {{ $T['missed_punch_outs'] ? 'is-bad' : 'is-muted' }}"><div class="xa-stat-label">Missed punch-outs</div><div class="xa-stat-value">{{ $T['missed_punch_outs'] }}</div></div>
        <div class="xa-stat is-good"><div class="xa-stat-label">Avg hours</div><div class="xa-stat-value">{{ $T['avg_minutes'] !== null ? number_format($T['avg_minutes'] / 60, 1) . 'h' : '—' }}</div></div>
        @if ($pendingLeaves !== null)
            <a class="xa-stat {{ $pendingLeaves ? 'is-warn' : 'is-muted' }}" href="{{ route('leaves.admin') }}" style="text-decoration:none"><div class="xa-stat-label">Pending leave</div><div class="xa-stat-value">{{ $pendingLeaves }}</div><div class="xa-stat-hint">Review →</div></a>
        @endif
    </div>
    <div class="xa-card">
        @include('attendance.partials.performance-table', ['rows' => $rows, 'compact' => true, 'periodQuery' => ['from' => $from->toDateString(), 'to' => $to->toDateString()]])
    </div>
</div>
