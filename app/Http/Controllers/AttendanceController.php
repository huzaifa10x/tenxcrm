<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\GeneralSetting;
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
        if (in_array($now->toDateString(), $settings->holidays)) {
            return response()->json(['error' => 'Today is a holiday!'], 403);
        }

        // Location validation
        $officeLatitude = 24.8607;
        $officeLongitude = 67.0011;
        $radius = 0.5; // 500 meters

        if (!$request->latitude || !$request->longitude) {
            return response()->json(['error' => 'Location access denied!'], 403);
        }

        $distance = $this->haversineDistance($officeLatitude, $officeLongitude, $request->latitude, $request->longitude);
        if ($distance > $radius) {
            return response()->json(['error' => 'You are not in the office!'], 403);
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
}
