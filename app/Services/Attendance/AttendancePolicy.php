<?php

namespace App\Services\Attendance;

use App\Models\Attendance;
use App\Models\GeneralSetting;
use Carbon\Carbon;
use Carbon\CarbonInterface;

/**
 * Company attendance rules, read from the existing general_settings row with safe defaults.
 * All times are in the app timezone (Asia/Dubai).
 */
class AttendancePolicy
{
    public string $open;            // H:i:s

    public string $close;           // H:i:s

    public int $graceMinutes;       // late after open + grace

    public string $halfDayAfter;    // punch-in after this = half day

    public string $earlyOutBefore;  // punch-out before this = early out

    public int $earlyPunchInMinutes;

    public array $workingDays;      // ISO weekdays 1..7

    public array $holidays;         // Y-m-d strings

    public bool $configured;

    private static ?self $cached = null;

    public function __construct(?GeneralSetting $s)
    {
        $this->configured = (bool) $s;
        $this->open = self::time($s->office_open_time ?? null, '09:00:00');
        $this->close = self::time($s->office_close_time ?? null, '18:00:00');
        $this->graceMinutes = (int) ($s->late_threshold ?? 15);
        $this->halfDayAfter = self::time($s->half_day_threshold ?? null, '12:00:00');
        $this->earlyOutBefore = self::time($s->early_out_threshold ?? null, $this->close);
        $this->earlyPunchInMinutes = (int) ($s->early_punch_in_minutes ?? 15);
        $days = array_values(array_filter(array_map('intval', (array) ($s->working_days ?? [])), fn ($d) => $d >= 1 && $d <= 7));
        $this->workingDays = $days ?: [1, 2, 3, 4, 5];
        $this->holidays = array_values(array_filter(array_map(fn ($d) => trim((string) $d, " \"'[]"), (array) ($s->holidays ?? []))));
    }

    public static function current(): self
    {
        return self::$cached ??= new self(GeneralSetting::first());
    }

    /** Forget the cached copy (after settings change, and between tests). */
    public static function refresh(): self
    {
        self::$cached = null;

        return self::current();
    }

    private static function time(?string $value, string $default): string
    {
        return $value ? Carbon::parse($value)->format('H:i:s') : $default;
    }

    public function at(CarbonInterface|string $date, string $time): Carbon
    {
        return Carbon::parse(Carbon::parse($date)->toDateString().' '.$time);
    }

    public function isHoliday(CarbonInterface|string $date): bool
    {
        return in_array(Carbon::parse($date)->toDateString(), $this->holidays, true);
    }

    public function isWeekend(CarbonInterface|string $date): bool
    {
        return ! in_array(Carbon::parse($date)->dayOfWeekIso, $this->workingDays, true);
    }

    public function isWorkingDay(CarbonInterface|string $date): bool
    {
        return ! $this->isWeekend($date) && ! $this->isHoliday($date);
    }

    public function lateThreshold(CarbonInterface|string $date): Carbon
    {
        return $this->at($date, $this->open)->addMinutes($this->graceMinutes);
    }

    /**
     * Until this moment on $day, the PREVIOUS day's open record can still be punched out
     * (overnight work). On working days that's when today's punch-in window opens; on other
     * days it's noon.
     */
    public function overnightCutoff(CarbonInterface|string $day): Carbon
    {
        return $this->isWorkingDay($day)
            ? $this->at($day, $this->open)->subMinutes($this->earlyPunchInMinutes)
            : $this->at($day, '12:00:00');
    }

    /** Status for a punch-in at $at, using the same rules as before. */
    public function statusForPunchIn(Carbon $at): string
    {
        if ($at->gt($this->at($at, $this->halfDayAfter))) {
            return 'half_day';
        }

        return $at->gt($this->lateThreshold($at)) ? 'late' : 'present';
    }

    /** Minutes after office opening, when the punch-in was past the grace period. */
    public function lateMinutes(Attendance $a): int
    {
        if (! $a->punch_in) {
            return 0;
        }
        $in = $this->at($a->dateString(), $a->punch_in);

        return $in->gt($this->lateThreshold($in)) ? (int) $this->at($in, $this->open)->diffInMinutes($in) : 0;
    }

    /** Minutes before the early-out threshold, for a real (not auto-closed) punch-out. */
    public function earlyOutMinutes(Attendance $a): int
    {
        if (! $a->punch_out || $a->auto_closed) {
            return 0;
        }
        $out = $this->at($a->dateString(), $a->punch_out);
        if ($a->punch_in && $out->lt($this->at($a->dateString(), $a->punch_in))) {
            return 0; // punched out after midnight
        }
        $limit = $this->at($a->dateString(), $this->earlyOutBefore);

        return $out->lt($limit) ? (int) $out->diffInMinutes($limit) : 0;
    }

    /** Worked minutes from total_hours, or from the punch times. */
    public function workedMinutes(Attendance $a): ?int
    {
        if (! $a->punch_in || ! $a->punch_out) {
            return null;
        }
        if ($a->total_hours && preg_match('/^(\d+):(\d+)(?::(\d+))?$/', $a->total_hours, $m)) {
            return (int) $m[1] * 60 + (int) $m[2];
        }
        $in = $this->at($a->dateString(), $a->punch_in);
        $out = $this->at($a->dateString(), $a->punch_out);
        if ($out->lt($in)) {
            $out->addDay();
        }

        return (int) $in->diffInMinutes($out);
    }

    /** Scheduled working minutes per day (office open → close). */
    public function scheduledMinutes(): int
    {
        return (int) Carbon::parse($this->open)->diffInMinutes(Carbon::parse($this->close));
    }

    public static function formatMinutes(?int $minutes): string
    {
        if ($minutes === null) {
            return '—';
        }

        return intdiv($minutes, 60).'h '.str_pad((string) ($minutes % 60), 2, '0', STR_PAD_LEFT).'m';
    }
}
