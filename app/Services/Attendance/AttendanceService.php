<?php

namespace App\Services\Attendance;

use App\Models\Attendance;
use App\Models\AttendanceNote;
use App\Models\Automation;
use App\Models\LeaveRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Punch in / punch out, the daily close (missed punch-outs and absences) and admin corrections.
 *
 * Overnight work: a record stays open until the next day's punch-in window opens (or noon on a
 * day off), so someone who punches in at 18:00 can punch out at 02:00 and the record keeps the
 * original date. After that it's treated as a missed punch-out.
 */
class AttendanceService
{
    public function __construct(private ?AttendancePolicy $policy = null)
    {
        $this->policy ??= AttendancePolicy::current();
    }

    public function policy(): AttendancePolicy
    {
        return $this->policy;
    }

    /* ------------------------------------------------------------------ punch */

    /**
     * @return array{ok:bool, message:string, attendance?:Attendance}
     */
    public function punchIn(User $user, ?float $lat = null, ?float $lng = null, ?Carbon $now = null): array
    {
        $now ??= Carbon::now();
        $p = $this->policy;

        if (! $p->configured) {
            return $this->fail('Company settings not configured!');
        }
        if (! $user->track_attendance) {
            return $this->fail('Attendance is not tracked for your account.');
        }
        if ($p->isHoliday($now)) {
            return $this->fail('Today is a holiday!');
        }
        if ($p->isWeekend($now)) {
            return $this->fail('Today is not a working day.');
        }
        $open = $p->at($now, $p->open);
        if ($now->lt($open->copy()->subMinutes($p->earlyPunchInMinutes))) {
            return $this->fail("Punch-in is only allowed {$p->earlyPunchInMinutes} minutes before office opens!");
        }

        if ($this->approvedLeaveOn($user, $now->toDateString())) {
            return $this->fail('You are on approved leave today.');
        }

        return $this->locked($user, function () use ($user, $now, $open, $p, $lat, $lng) {
            return DB::transaction(function () use ($user, $now, $open, $p, $lat, $lng) {
                $date = $now->toDateString();

                // Anything still open from earlier days is a missed punch-out.
                Attendance::where('user_id', $user->id)->whereDate('date', '<', $date)
                    ->whereNotNull('punch_in')->whereNull('punch_out')->lockForUpdate()->get()
                    ->each(fn ($a) => $this->autoClose($a));
                $existing = Attendance::where('user_id', $user->id)->whereDate('date', $date)->lockForUpdate()->first();

                if ($existing && ($existing->punch_in || in_array($existing->status, ['leave', 'holiday'], true))) {
                    return $this->fail($existing->punch_out
                        ? 'You have already punched in and out today. You can punch in again tomorrow.'
                        : 'Already punched in!');
                }

                // Early punch-ins are recorded at office opening time (existing behaviour).
                $punchIn = $now->lt($open) ? $open : $now;
                $data = [
                    'punch_in' => $punchIn->format('H:i:s'),
                    'punch_out' => null,
                    'total_hours' => null,
                    'status' => $p->statusForPunchIn($now),
                    'location' => ($lat !== null && $lng !== null) ? round($lat, 7).','.round($lng, 7) : null,
                    'auto_closed' => false,
                ];

                // A row may already exist without a punch-in (e.g. marked absent by an admin).
                $attendance = $existing
                    ? tap($existing)->update($data)
                    : Attendance::create($data + ['user_id' => $user->id, 'date' => $date]);

                return ['ok' => true, 'message' => 'Punched in successfully!', 'attendance' => $attendance];
            });
        });
    }

    /**
     * @return array{ok:bool, message:string, attendance?:Attendance}
     */
    public function punchOut(User $user, ?Carbon $now = null): array
    {
        $now ??= Carbon::now();

        return $this->locked($user, function () use ($user, $now) {
            return DB::transaction(function () use ($user, $now) {
                $attendance = $this->openRecord($user, $now, true)
                    ?? Attendance::where('user_id', $user->id)->whereDate('date', $now->toDateString())->whereNotNull('punch_in')->whereNull('punch_out')->lockForUpdate()->first();

                if (! $attendance) {
                    $today = Attendance::where('user_id', $user->id)->whereDate('date', $now->toDateString())->first();

                    return $this->fail($today && $today->punch_out
                        ? 'You have already punched in and out today. You can punch in again tomorrow.'
                        : "You haven't punched in today.");
                }

                $in = $this->policy->at($attendance->dateString(), $attendance->punch_in);
                if ($now->lt($in)) {
                    return $this->fail('Punch-out time cannot be before punch-in time.');
                }

                $attendance->punch_out = $now->format('H:i:s');
                $attendance->total_hours = self::duration($in, $now);
                $attendance->auto_closed = false;
                // Early-out only replaces a plain "present"; late / half-day stay visible.
                if ($attendance->status === 'present' && $this->policy->earlyOutMinutes($attendance) > 0) {
                    $attendance->status = 'early_out';
                }
                $attendance->save();

                return ['ok' => true, 'message' => 'Punched out successfully!', 'attendance' => $attendance];
            });
        });
    }

    /**
     * An open record from a PREVIOUS day that can still be punched out (overnight work).
     */
    public function openRecord(User $user, Carbon $now, bool $lock = false): ?Attendance
    {
        if ($now->gte($this->policy->overnightCutoff($now))) {
            return null;
        }

        $q = Attendance::where('user_id', $user->id)
            ->whereDate('date', $now->copy()->subDay()->toDateString())
            ->whereNotNull('punch_in')
            ->whereNull('punch_out');

        return ($lock ? $q->lockForUpdate() : $q)->first();
    }

    /**
     * The widget's view of "today": the open overnight record if there is one, otherwise today's.
     */
    public function current(User $user, ?Carbon $now = null): ?Attendance
    {
        $now ??= Carbon::now();

        return $this->openRecord($user, $now)
            ?? Attendance::where('user_id', $user->id)->whereDate('date', $now->toDateString())->first();
    }

    public function approvedLeaveOn(User $user, string $date): ?LeaveRequest
    {
        return LeaveRequest::where('user_id', $user->id)
            ->where('status', 'approved')
            ->overlapping($date, $date)
            ->with('type')
            ->first();
    }

    /* -------------------------------------------------------------- daily close */

    /**
     * Close every finished day up to yesterday (or the day before, before noon):
     *  - open records get punch_out = office closing time and auto_closed = true (missed punch-out)
     *  - tracked employees with no record on a working day get "leave" (approved leave) or "absent"
     * Safe to run many times; it only ever adds missing rows.
     */
    public function closeThrough(?Carbon $now = null, int $maxDays = 31): int
    {
        $now ??= Carbon::now();
        $through = $now->copy()->subDay()->startOfDay();
        if ($now->lt($this->policy->overnightCutoff($now))) {
            $through->subDay();
        }

        $automation = Automation::firstOrCreate(['key' => 'attendance_closed_through']);
        $from = $automation->last_run_date
            ? Carbon::parse($automation->last_run_date)->addDay()->startOfDay()
            : $through->copy();
        if ($from->lt($through->copy()->subDays($maxDays - 1))) {
            $from = $through->copy()->subDays($maxDays - 1);
        }

        $count = 0;
        for ($d = $from->copy(); $d->lte($through); $d->addDay()) {
            $count += $this->closeDay($d->toDateString());
        }

        if (! $automation->last_run_date || $through->gt($automation->last_run_date)) {
            $automation->update(['last_run_date' => $through->toDateString()]);
        }

        return $count;
    }

    /** Close one date. Returns the number of rows created or closed. */
    public function closeDay(string $date): int
    {
        $p = $this->policy;
        $count = 0;

        // 1. Missed punch-outs.
        $open = Attendance::whereDate('date', $date)->whereNotNull('punch_in')->whereNull('punch_out')->get();
        foreach ($open as $a) {
            $this->autoClose($a);
            $count++;
        }

        if (! $p->isWorkingDay($date)) {
            return $count;
        }

        // 2. Absences / leave for working days.
        $users = User::tracked()
            ->where(fn ($q) => $q->whereNull('created_at')->orWhereDate('created_at', '<=', $date))
            ->whereDoesntHave('attendances', fn ($q) => $q->whereDate('date', $date))
            ->get(['id']);

        $onLeave = LeaveRequest::where('status', 'approved')->overlapping($date, $date)->pluck('user_id')->all();

        foreach ($users as $u) {
            try {
                Attendance::create([
                    'user_id' => $u->id,
                    'date' => $date,
                    'status' => in_array($u->id, $onLeave) ? 'leave' : 'absent',
                ]);
                $count++;
            } catch (QueryException $e) {
                // Another process created it first (unique index) — fine.
            }
        }

        return $count;
    }

    /** Missed punch-out: close at office closing time and flag it. */
    private function autoClose(Attendance $a): void
    {
        $date = $a->dateString();
        $in = $this->policy->at($date, $a->punch_in);
        $out = $this->policy->at($date, $this->policy->close);
        if ($out->lte($in)) {
            $out = $in->copy(); // punched in after closing: zero hours rather than a negative time
        }
        $a->update(['punch_out' => $out->format('H:i:s'), 'total_hours' => self::duration($in, $out), 'auto_closed' => true]);
    }

    /* ------------------------------------------------------------ admin edits */

    /**
     * Admin correction of a record. The change and reason are kept as an attendance note.
     */
    public function correct(Attendance $attendance, array $data, User $editor, string $reason): Attendance
    {
        return DB::transaction(function () use ($attendance, $data, $editor, $reason) {
            $before = $attendance->only(['punch_in', 'punch_out', 'status']);
            $date = $attendance->dateString();
            $p = $this->policy;

            $in = ! empty($data['punch_in']) ? Carbon::parse($data['punch_in'])->format('H:i:s') : null;
            $out = ! empty($data['punch_out']) ? Carbon::parse($data['punch_out'])->format('H:i:s') : null;
            $status = $data['status'] ?? null;

            if (! $in) {
                $out = null;
                $status = in_array($status, ['absent', 'leave', 'holiday'], true) ? $status : 'absent';
            } elseif (! $status || in_array($status, ['absent', 'leave', 'holiday'], true)) {
                $status = $p->statusForPunchIn($p->at($date, $in));
            }

            $total = null;
            if ($in && $out) {
                $inAt = $p->at($date, $in);
                $outAt = $p->at($date, $out);
                if ($outAt->lt($inAt)) {
                    $outAt->addDay(); // overnight
                }
                $total = self::duration($inAt, $outAt);
            }

            $attendance->update([
                'punch_in' => $in,
                'punch_out' => $out,
                'total_hours' => $total,
                'status' => $status,
                'auto_closed' => false,
                'edited_by' => $editor->id,
                'edited_at' => now(),
            ]);

            $after = $attendance->only(['punch_in', 'punch_out', 'status']);
            $changes = collect($after)->filter(fn ($v, $k) => $before[$k] != $v)
                ->map(fn ($v, $k) => str_replace('_', ' ', $k).': '.($before[$k] ?: '—').' → '.($v ?: '—'))
                ->implode(', ');

            AttendanceNote::create([
                'attendance_id' => $attendance->id,
                'user_id' => $editor->id,
                'note' => '[Correction] '.($changes ?: 'no field changes').' — '.$reason,
            ]);

            return $attendance->fresh();
        });
    }

    /** Admin creates a record for a day that has none. */
    public function createFor(User $employee, string $date, array $data, User $editor, string $reason): Attendance
    {
        $attendance = Attendance::firstOrCreate(
            ['user_id' => $employee->id, 'date' => $date],
            ['status' => 'absent']
        );

        return $this->correct($attendance, $data, $editor, $reason);
    }

    /* ---------------------------------------------------------------- helpers */

    public static function duration(Carbon $from, Carbon $to): string
    {
        $seconds = max(0, $from->diffInSeconds($to, false));
        $seconds = min($seconds, 23 * 3600 + 59 * 60 + 59); // TIME column, single shift

        return sprintf('%02d:%02d:%02d', intdiv($seconds, 3600), intdiv($seconds % 3600, 60), $seconds % 60);
    }

    private function fail(string $message): array
    {
        return ['ok' => false, 'message' => $message];
    }

    /** Per-user lock so double clicks / two devices can't both punch. */
    private function locked(User $user, callable $callback): array
    {
        try {
            return Cache::lock('attendance-punch:'.$user->id, 10)->block(5, $callback);
        } catch (LockTimeoutException) {
            return $this->fail('Another punch is being processed. Please try again.');
        } catch (QueryException $e) {
            // Unique index caught a race the lock didn't (e.g. cache store without locks).
            if (str_contains($e->getMessage(), 'attendances_user_date_unique') || $e->getCode() === '23000') {
                return $this->fail('Already punched in!');
            }
            throw $e;
        }
    }
}
