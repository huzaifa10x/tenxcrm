<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\GeneralSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function punchIn(Request $request)
    {
        $user = Auth::user();
        $settings = GeneralSetting::first();
        $now = Carbon::now();

        if (!$settings) {
            return response()->json(['error' => 'Company settings not configured!'], 403);
        }

        // Office open/close time
        $officeOpeningTime = Carbon::parse($settings->office_open_time);
        $earlyPunchInTime = $officeOpeningTime->copy()->subMinutes(15);
        $officeClosingTime = Carbon::parse($settings->office_close_time);

        $halfDayThreshold = Carbon::parse($settings->half_day_threshold ?? '12:00:00')->toTimeString();

        $lateThresholdMinutes = (int) $settings->late_threshold;
        $lateThreshold = $officeOpeningTime->copy()->addMinutes($lateThresholdMinutes)->format('H:i:s');

        // Holidays check
        if (in_array($now->toDateString(), $settings->holidays) || $now->isWeekend()) {
            return response()->json(['error' => 'Today is a holiday!'], 403);
        }

        // Time validation: Allow punch-in 15 mins before but store official opening time
        if ($now->lt($earlyPunchInTime)) {
            return response()->json(['error' => 'Punch-in is only allowed 15 minutes before office opens!'], 403);
        }

        $status = 'present';
        if ($now->gt($lateThreshold)) {
            $status = 'late';
        }

        if ($now->gt($halfDayThreshold)) {
            $status = 'half_day';
        }

        // Already punched in?
        if (Attendance::where('user_id', $user->id)->whereDate('date', Carbon::today())->exists()) {
            return response()->json(['error' => 'Already punched in!'], 403);
        }

        // If punching in early, set the punch-in time as office opening time
        $punchInTime = $now->lt($officeOpeningTime) ? $officeOpeningTime->toTimeString() : $now->toTimeString();

        Attendance::create([
            'user_id' => $user->id,
            'date' => Carbon::today(),
            'punch_in' => $punchInTime,
            'location' => $request->latitude . ',' . $request->longitude,
            'status' => $status,
        ]);

        return response()->json(['success' => 'Punched in successfully!']);
    }
    public function punchOut()
    {
        $user = Auth::user();
        $attendance = Attendance::where('user_id', $user->id)->whereDate('date', Carbon::today())->first();

        $generalSettings = \App\Models\GeneralSetting::first();
        $earlyOutThreshold = $generalSettings->early_out_threshold ?? $generalSettings->office_close_time;

        if (!$attendance || $attendance->punch_out) {
            return response()->json(['error' => 'You have already punched in and out today. You can punch in again tomorrow.!'], 403);
        }

        $now = Carbon::now();

        $status = $attendance->status;
        if ($now->lt($earlyOutThreshold)) {
            $status = 'early_out';
        }

        $attendance->punch_out = $now->toTimeString();
        $attendance->total_hours = Carbon::parse($attendance->punch_in)->diff($now)->format('%H:%I:%S');
        $attendance->status = $status;
        $attendance->save();

        return response()->json(['success' => 'Punched out successfully!']);
    }

    private function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Radius of Earth in KM
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c; // Distance in KM
    }

    public function monthlyAttendance(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $authUser = auth()->user();
        $userId = $authUser->hasRole('ADMIN')
            ? $request->input('user_id')
            : $authUser->id; // force their own ID if not admin

        // Office Settings
        $generalSettings = GeneralSetting::first();
        $officeStartTime = $generalSettings->office_open_time ?? '09:00:00';
        $halfDayThreshold = $generalSettings->half_day_threshold ?? '12:00:00';
        $breakEndTime = $generalSettings->break_end_time ?? '14:00:00';
        $earlyOutThreshold = $generalSettings->early_out_threshold ?? $generalSettings->office_close_time;
        $lateThreshold = Carbon::createFromFormat('H:i:s', $officeStartTime)
            ->addMinutes($generalSettings->late_threshold)->format('H:i:s');

        $late_deduction_threshold = $generalSettings->late_deduction_threshold ?? 3;
        $half_day_deduction_threshold = $generalSettings->half_day_deduction_threshold ?? 2;

        $monthCarbon = Carbon::parse($month);
        $totalDays = $monthCarbon->daysInMonth;
        $holidays = $generalSettings->holidays ?? [];

        // Users Query
        $usersQuery = User::query();
        if (!$authUser->hasRole('ADMIN')) {
            $usersQuery->where('id', $authUser->id);
        } elseif ($userId) {
            $usersQuery->where('id', $userId);
        }

        $users = $usersQuery->with(['attendances' => function ($query) use ($monthCarbon) {
            $query->whereYear('date', $monthCarbon->year)
                ->whereMonth('date', $monthCarbon->month);
        }])->get();

        $totalPresent = $totalAbsent = $totalLate = $totalHalfDay = $totalEarlyOut = $deductions = 0;

        if ($userId) {
            $user = $users->first();
            if ($user) {
                $lateCount = $halfDayCount = 0;

                for ($day = 1; $day <= $totalDays; $day++) {
                    $date = $monthCarbon->format('Y-m') . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                    $carbonDate = Carbon::parse($date);

                    if (in_array($date, $holidays) || $carbonDate->isWeekend()) continue;

                    $attendance = $user->attendances->firstWhere('date', $date);

                    if ($attendance) {
                        switch ($attendance->status) {
                            case 'present':
                                $totalPresent++;
                                $lateCount = $halfDayCount = 0;
                                break;
                            case 'late':
                                $totalLate++;
                                $lateCount++;
                                if ($lateCount == $late_deduction_threshold) {
                                    $deductions++;
                                    $lateCount = 0;
                                }
                                break;
                            case 'half_day':
                                $totalHalfDay++;
                                $halfDayCount++;
                                if ($halfDayCount == $half_day_deduction_threshold) {
                                    $deductions++;
                                    $halfDayCount = 0;
                                }
                                break;
                            case 'early_out':
                                $totalEarlyOut++;
                                break;
                            case 'absent':
                                $totalAbsent++;
                                $lateCount = $halfDayCount = 0;
                                break;
                        }
                    } else {
                        if ($carbonDate->isPast() && !$carbonDate->isToday()) {
                            $totalAbsent++;
                        }
                        $lateCount = $halfDayCount = 0;
                    }
                }
            }
        }

        $todayAttendance = Attendance::where('user_id', auth()->id())->where('date', today())->first();
        $todayAttendanceId = $todayAttendance?->id ?? null;
        
        return view('attendance.index', compact(
            'users',
            'breakEndTime',
            'earlyOutThreshold',
            'halfDayThreshold',
            'lateThreshold',
            'month',
            'totalDays',
            'userId',
            'totalPresent',
            'totalAbsent',
            'totalLate',
            'totalHalfDay',
            'totalEarlyOut',
            'deductions',
            'holidays',
            'todayAttendanceId',
        ));
    }
}
