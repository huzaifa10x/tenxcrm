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
        $officeClosingTime = Carbon::parse($settings->office_close_time);

        // Holidays check
        if (in_array($now->toDateString(), $settings->holidays) || $now->isWeekend()) {
            return response()->json(['error' => 'Today is a holiday!'], 403);
        }

        // Location validation
        $officeLatitude = 24.904871745119998;
        $officeLongitude = 67.07809279592651;
        $radius = 0.5; // 500 meters
        if (!$request->latitude || !$request->longitude) {
            // return response()->json(['error' => 'Location access denied!'], 403);
        }

        $distance = $this->haversineDistance($officeLatitude, $officeLongitude, $request->latitude, $request->longitude);
        if ($distance > $radius) {
            // return response()->json(['error' => 'You are not in the office!'], 403);
        }

        // Time validation
        if ($now->lt($officeOpeningTime)) {
            return response()->json(['error' => 'Office is not open yet!'], 403);
        }

        // Already punched in?
        if (Attendance::where('user_id', $user->id)->whereDate('date', Carbon::today())->exists()) {
            return response()->json(['error' => 'Already punched in!'], 403);
        }

        Attendance::create([
            'user_id' => $user->id,
            'date' => Carbon::today(),
            'punch_in' => $now->toTimeString(),
            'location' => $request->latitude . ',' . $request->longitude,
        ]);

        return response()->json(['success' => 'Punched in successfully!']);
    }

    public function punchOut()
    {
        $user = Auth::user();
        $attendance = Attendance::where('user_id', $user->id)->whereDate('date', Carbon::today())->first();

        if (!$attendance || $attendance->punch_out) {
            return response()->json(['error' => 'You have already punched in and out today. You can punch in again tomorrow.!'], 403);
        }

        $now = Carbon::now();
        $attendance->punch_out = $now->toTimeString();
        $attendance->total_hours = Carbon::parse($attendance->punch_in)->diff($now)->format('%H:%I:%S');
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
        $userId = $request->input('user_id'); // User filter

        // Get office settings
        $generalSettings = \App\Models\GeneralSetting::first();
        $officeStartTime = $generalSettings->office_open_time ?? '09:00:00';
        $halfDayThreshold = $generalSettings->half_day_threshold ?? '12:00:00';
        $breakEndTime = $generalSettings->break_end_time ?? '14:00:00';
        $earlyOutThreshold = $generalSettings->early_out_threshold ?? $generalSettings->office_close_time;

        $lateThreshold = \Carbon\Carbon::createFromFormat('H:i:s', $officeStartTime)->addMinutes($generalSettings->late_threshold)->format('H:i:s');

        // Convert month to Carbon instance
        $monthCarbon = \Carbon\Carbon::parse($month);
        $totalDays = $monthCarbon->daysInMonth;
        $holidays = $generalSettings->holidays ?? []; // DB se holidays get karo

        // Fetch users (filtered if user_id is provided)
        $usersQuery = \App\Models\User::query();
        if ($userId) {
            $usersQuery->where('id', $userId);
        }

        $users = $usersQuery->with(['attendances' => function ($query) use ($monthCarbon) {
            $query->whereYear('date', $monthCarbon->year)
                ->whereMonth('date', $monthCarbon->month);
        }])->get();

        // Initialize attendance counters
        $totalPresent = $totalAbsent = $totalLate = $totalHalfDay = $totalEarlyOut = $totalATC = 0;

        if ($userId) {
            $user = $users->first();
            if ($user) {
                for ($day = 1; $day <= $totalDays; $day++) {
                    $date = $monthCarbon->format('Y-m') . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                    $carbonDate = \Carbon\Carbon::parse($date);

                    // Skip holidays and weekends
                    $isHoliday = in_array($date, $holidays) || $carbonDate->isWeekend();
                    if ($isHoliday) continue;

                    // Get attendance
                    $attendance = $user->attendances->firstWhere('date', $date);

                    if ($attendance) {
                        $totalPresent++;
                        $totalATC++; // Attended Days Count

                        // Late Handling
                        if ($attendance->punch_in > $lateThreshold) {
                            $totalLate++;
                        }

                        // Half-Day Handling
                        if ($attendance->punch_in > $halfDayThreshold) {
                            $totalHalfDay++;
                        }

                        // Early Out Handling
                        if ($attendance->punch_out && $attendance->punch_out < $earlyOutThreshold && $attendance->punch_out > $breakEndTime) {
                            $totalEarlyOut++;
                        }
                    } else {
                        $totalAbsent++;
                    }
                }
            }
        }

        return view('attendance.index', compact('users', 'breakEndTime', 'earlyOutThreshold', 'halfDayThreshold', 'lateThreshold', 'month', 'totalDays', 'userId', 'totalPresent', 'totalAbsent', 'totalLate', 'totalHalfDay', 'totalEarlyOut', 'totalATC', 'holidays'));
    }
}
