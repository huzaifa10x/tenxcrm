<?php

namespace App\View\Components\Dashboards;

use App\Models\Attendance;
use App\Models\GeneralSetting;
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
        $today = Carbon::today();
        $settings = GeneralSetting::first();

        if ($settings) {
            $this->officeHours = Carbon::parse($settings->office_open_time)->format('h:i A')
                . ' – ' . Carbon::parse($settings->office_close_time)->format('h:i A');

            if ($today->isWeekend() || in_array($today->toDateString(), $settings->holidays ?? [], true)) {
                $this->state = 'off';
                $this->offReason = $today->isWeekend() ? 'Weekend' : 'Holiday';
            }
        }

        $attendance = Auth::check()
            ? Attendance::where('user_id', Auth::id())->whereDate('date', $today)->first()
            : null;

        if ($attendance) {
            $in = Carbon::parse($today->toDateString() . ' ' . $attendance->punch_in);
            $this->punchIn = $in->toIso8601String();
            $this->punchInLabel = $in->format('h:i A');
            $this->status = $attendance->status;
            $this->state = 'working';

            if ($attendance->punch_out) {
                $this->state = 'done';
                $this->punchOutLabel = Carbon::parse($attendance->punch_out)->format('h:i A');
                $this->totalHours = self::formatDuration($attendance->total_hours);
            }
        }
    }

    /** "08:12:45" → "8h 12m" */
    public static function formatDuration(?string $hms): ?string
    {
        if (!$hms || !preg_match('/^(\d+):(\d+)/', $hms, $m)) {
            return $hms;
        }

        return ((int) $m[1]) . 'h ' . str_pad((string) (int) $m[2], 2, '0', STR_PAD_LEFT) . 'm';
    }

    public function render(): View|Closure|string
    {
        return view('components.dashboards.punch-in-out');
    }
}
