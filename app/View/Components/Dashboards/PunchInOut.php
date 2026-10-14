<?php

namespace App\View\Components\Dashboards;

use App\Services\Attendance\AttendancePolicy;
use App\Services\Attendance\AttendanceService;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

/**
 * Attendance clock widget: shows today's status for the signed-in user and a
 * single action button (Punch In → Punch Out → Done).
 */
class PunchInOut extends Component
{
    /** idle | working | done | off */
    public string $state = 'idle';

    public ?string $punchIn = null;      // ISO timestamp, for the live timer

    public ?string $punchInLabel = null; // 09:12 AM

    public ?string $punchOutLabel = null;

    public ?string $totalHours = null;

    public ?string $status = null;

    public ?string $officeHours = null;

    public ?string $offReason = null;

    public function __construct()
    {
        $policy = AttendancePolicy::current();
        $today = Carbon::today();

        if ($policy->configured) {
            $this->officeHours = Carbon::parse($policy->open)->format('h:i A')
                .' – '.Carbon::parse($policy->close)->format('h:i A');

            if (! $policy->isWorkingDay($today)) {
                $this->state = 'off';
                $this->offReason = $policy->isHoliday($today) ? 'Holiday' : 'Weekend';
            }
        }

        $user = Auth::user();
        if (! $user) {
            return;
        }

        if ($user->track_attendance === false) {
            $this->state = 'off';
            $this->offReason = 'Not tracked';

            return;
        }

        // Today's record, or last night's shift that is still open.
        $service = app(AttendanceService::class);
        $attendance = $service->current($user);

        if ($attendance && $attendance->punch_in) {
            $in = $policy->at($attendance->dateString(), $attendance->punch_in);
            $this->punchIn = $in->toIso8601String();
            $this->punchInLabel = $in->format('h:i A');
            $this->status = $attendance->status;
            $this->state = 'working';

            if ($attendance->punch_out) {
                $this->state = 'done';
                $this->punchOutLabel = Carbon::parse($attendance->punch_out)->format('h:i A');
                $this->totalHours = self::formatDuration($attendance->total_hours);
            }
        } elseif ($service->approvedLeaveOn($user, $today->toDateString()) || $attendance?->status === 'leave') {
            $this->state = 'off';
            $this->offReason = 'On leave';
        }
    }

    /** "08:12:45" → "8h 12m" */
    public static function formatDuration(?string $hms): ?string
    {
        if (! $hms || ! preg_match('/^(\d+):(\d+)/', $hms, $m)) {
            return $hms;
        }

        return ((int) $m[1]).'h '.str_pad((string) (int) $m[2], 2, '0', STR_PAD_LEFT).'m';
    }

    public function render(): View|Closure|string
    {
        return view('components.dashboards.punch-in-out');
    }
}
