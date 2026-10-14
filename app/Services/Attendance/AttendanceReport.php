<?php

namespace App\Services\Attendance;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

/**
 * Attendance numbers for a set of employees over a date range, computed from the actual
 * records and the company policy (working days, holidays, office hours, grace period).
 *
 * Definitions
 *  - Working day: a configured working weekday that is not a public holiday.
 *  - Elapsed working days: working days before today, plus today once the person has punched in.
 *  - Present: a working day with a punch-in (half-days included).
 *  - Absent: an elapsed working day with no punch-in and no approved leave.
 *  - Leave: a working day covered by approved leave (or a record marked "leave").
 *  - Late: punched in after opening + grace (half-days counted separately).
 *  - Missed punch-out: punched in but never punched out (auto-closed, or still open from a past day).
 *  - Attendance % = (present − ½ × half-days) ÷ (elapsed working days − leave days).
 */
class AttendanceReport
{
    public function __construct(private ?AttendancePolicy $policy = null)
    {
        $this->policy ??= AttendancePolicy::current();
    }

    /**
     * @param  Collection<int,\App\Models\User>  $users
     * @return array{rows: Collection, totals: array, trend: array, from: string, to: string}
     */
    public function build(Collection $users, Carbon $from, Carbon $to): array
    {
        $from = $from->copy()->startOfDay();
        $to = $to->copy()->startOfDay();
        $ids = $users->pluck('id')->all();

        $records = Attendance::whereIn('user_id', $ids)
            ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
            ->withCount('notes')
            ->orderBy('id')
            ->get()
            ->groupBy('user_id')
            ->map(fn ($rows) => $rows->keyBy(fn ($r) => $r->dateString()));

        $leaves = LeaveRequest::whereIn('user_id', $ids)
            ->where('status', 'approved')
            ->overlapping($from->toDateString(), $to->toDateString())
            ->with('type:id,name,color')
            ->get()
            ->groupBy('user_id');

        $rows = $users->map(fn ($u) => $this->forUser($u, $records->get($u->id, collect()), $leaves->get($u->id, collect()), $from, $to))->values();

        return [
            'rows' => $rows,
            'totals' => $this->totals($rows, $from, $to),
            'trend' => $this->trend($rows, $from, $to),
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
        ];
    }

    /** Per-person summary plus day-by-day rows. */
    public function forUser($user, Collection $records, Collection $leaves, Carbon $from, Carbon $to): array
    {
        $p = $this->policy;
        $today = Carbon::today()->toDateString();
        // Records from this date on may still be open (overnight work until noon next day).
        $openFrom = Carbon::now()->lt($p->overnightCutoff(Carbon::today())) ? Carbon::yesterday()->toDateString() : $today;
        $joined = $user->created_at ? Carbon::parse($user->created_at)->toDateString() : null;

        $s = [
            'working_days' => 0, 'elapsed_days' => 0, 'present' => 0, 'absent' => 0, 'late' => 0,
            'late_minutes' => 0, 'half_days' => 0, 'early_outs' => 0, 'early_minutes' => 0,
            'missed_punch_outs' => 0, 'leave_days' => 0, 'holidays' => 0, 'worked_minutes' => 0, 'worked_days' => 0,
        ];
        $days = [];

        foreach (CarbonPeriod::create($from, $to) as $day) {
            $date = $day->toDateString();
            $rec = $records->get($date);
            $leave = $leaves->first(fn ($l) => $l->start_date->toDateString() <= $date && $l->end_date->toDateString() >= $date);
            $isWorking = $p->isWorkingDay($day);
            $beforeJoin = $joined && $date < $joined;

            $row = [
                'date' => $date,
                'id' => $rec?->id,
                'punch_in' => $rec?->punch_in,
                'punch_out' => $rec?->punch_out,
                'worked' => null,
                'late_minutes' => 0,
                'early_minutes' => 0,
                'missed_out' => false,
                'auto_closed' => (bool) $rec?->auto_closed,
                'edited' => (bool) $rec?->edited_at,
                'notes' => (int) ($rec->notes_count ?? 0),
                'leave' => $leave ? $leave->type?->name : null,
                'location' => $rec?->location,
                'raw_status' => $rec?->status,
            ];

            if ($rec && $rec->punch_in) {
                $late = $p->lateMinutes($rec);
                $isHalf = $rec->status === 'half_day';
                $row['late_minutes'] = $late;
                $row['early_minutes'] = $p->earlyOutMinutes($rec);
                $row['worked'] = $p->workedMinutes($rec);
                $stillOpen = ! $rec->punch_out && $date >= $openFrom;
                $row['missed_out'] = $rec->auto_closed || (! $rec->punch_out && ! $stillOpen);
                $row['status'] = $rec->status === 'early_out' && $late > 0 ? 'late' : ($rec->status ?: 'present');
                if ($stillOpen) {
                    $row['status'] = 'working';
                }

                // Worked time counts on any day (incl. weekend work), but not auto-closed guesses.
                if ($row['worked'] !== null && ! $row['missed_out']) {
                    $s['worked_minutes'] += $row['worked'];
                    $s['worked_days']++;
                }
                if ($isWorking) {
                    $s['present']++;
                    $s['half_days'] += $isHalf ? 1 : 0;
                    if (! $isHalf && ($rec->status === 'late' || $late > 0)) {
                        $s['late']++;
                        $s['late_minutes'] += $late;
                    }
                    if ($row['early_minutes'] > 0) {
                        $s['early_outs']++;
                        $s['early_minutes'] += $row['early_minutes'];
                    }
                    $s['missed_punch_outs'] += $row['missed_out'] ? 1 : 0;
                }
            } elseif (! $isWorking) {
                $row['status'] = $p->isHoliday($day) ? 'holiday' : 'weekend';
            } elseif ($beforeJoin) {
                $row['status'] = 'not_joined';
            } elseif ($leave || $rec?->status === 'leave') {
                $row['status'] = 'leave';
            } elseif ($date > $today) {
                $row['status'] = 'upcoming';
            } elseif ($date === $today) {
                $row['status'] = 'pending';
            } else {
                $row['status'] = 'absent';
            }

            if ($isWorking && ! $beforeJoin) {
                $s['working_days']++;
                if ($row['status'] === 'leave') {
                    $s['leave_days']++;
                }
                if ($date < $today || ($date === $today && $rec?->punch_in)) {
                    $s['elapsed_days']++;
                }
                if ($row['status'] === 'absent') {
                    $s['absent']++;
                }
            }
            if ($p->isHoliday($day) && ! $p->isWeekend($day)) {
                $s['holidays']++;
            }

            $days[] = $row;
        }

        $expected = max(0, $s['elapsed_days'] - $s['leave_days']);
        $s['attendance_pct'] = $expected > 0 ? round(max(0, $s['present'] - 0.5 * $s['half_days']) / $expected * 100, 1) : null;
        $s['avg_minutes'] = $s['worked_days'] > 0 ? (int) round($s['worked_minutes'] / $s['worked_days']) : null;

        return [
            'user' => $user,
            'summary' => $s,
            'days' => $days,
        ];
    }

    private function totals(Collection $rows, Carbon $from, Carbon $to): array
    {
        $sum = fn ($k) => $rows->sum(fn ($r) => $r['summary'][$k]);
        $pcts = $rows->pluck('summary.attendance_pct')->filter(fn ($v) => $v !== null);
        $workedDays = $sum('worked_days');

        return [
            'employees' => $rows->count(),
            'working_days' => $this->workingDays($from, $to),
            'present' => $sum('present'),
            'absent' => $sum('absent'),
            'late' => $sum('late'),
            'late_minutes' => $sum('late_minutes'),
            'half_days' => $sum('half_days'),
            'early_outs' => $sum('early_outs'),
            'leave_days' => $sum('leave_days'),
            'missed_punch_outs' => $sum('missed_punch_outs'),
            'avg_minutes' => $workedDays > 0 ? (int) round($sum('worked_minutes') / $workedDays) : null,
            'attendance_pct' => $pcts->isNotEmpty() ? round($pcts->avg(), 1) : null,
        ];
    }

    /** Daily team counts, for the trend chart. */
    private function trend(Collection $rows, Carbon $from, Carbon $to): array
    {
        $out = [];
        foreach (CarbonPeriod::create($from, $to) as $i => $day) {
            if (! $this->policy->isWorkingDay($day) || $day->isAfter(Carbon::today())) {
                continue;
            }
            $counts = ['present' => 0, 'late' => 0, 'absent' => 0, 'leave' => 0];
            foreach ($rows as $r) {
                $st = $r['days'][$i]['status'] ?? null;
                if (in_array($st, ['present', 'early_out', 'working', 'half_day'], true)) {
                    $counts['present']++;
                } elseif ($st === 'late') {
                    $counts['late']++;
                } elseif ($st === 'absent') {
                    $counts['absent']++;
                } elseif ($st === 'leave') {
                    $counts['leave']++;
                }
            }
            $out[] = ['date' => $day->toDateString(), 'label' => $day->format('d M')] + $counts;
        }

        return $out;
    }

    public function workingDays(Carbon $from, Carbon $to): int
    {
        $n = 0;
        foreach (CarbonPeriod::create($from, $to) as $day) {
            $n += $this->policy->isWorkingDay($day) ? 1 : 0;
        }

        return $n;
    }

    public static function statusLabel(string $status): string
    {
        return [
            'present' => 'Present', 'late' => 'Late', 'half_day' => 'Half day', 'early_out' => 'Early out',
            'absent' => 'Absent', 'leave' => 'On leave', 'holiday' => 'Holiday', 'weekend' => 'Weekend',
            'upcoming' => '—', 'pending' => 'Not punched in', 'working' => 'Working', 'not_joined' => '—',
        ][$status] ?? ucfirst(str_replace('_', ' ', $status));
    }
}
