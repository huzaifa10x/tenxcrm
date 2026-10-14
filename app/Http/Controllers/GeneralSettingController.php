<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use App\Services\Attendance\AttendancePolicy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GeneralSettingController extends Controller
{
    public function index()
    {
        // Non-admins see the existing "not allowed" message from the view.
        $settings = GeneralSetting::first();

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // Previously any signed-in user could post here.
        abort_unless(Auth::user()->hasRole('ADMIN'), 403);

        $request->validate([
            'office_open_time' => 'required|date_format:H:i,H:i:s',
            'office_close_time' => 'required|date_format:H:i,H:i:s',
            'late_threshold' => 'required|integer|min:0|max:240',
            'late_deduction_threshold' => 'required|integer|min:1|max:31',
            'half_day_deduction_threshold' => 'required|integer|min:1|max:31',
            'half_day_threshold' => 'required|date_format:H:i,H:i:s',
            'break_end_time' => 'required|date_format:H:i,H:i:s',
            'early_out_threshold' => 'required|date_format:H:i,H:i:s',
            'early_punch_in_minutes' => 'nullable|integer|min:0|max:240',
            'working_days' => 'nullable|array|min:1',
            'working_days.*' => 'integer|between:1,7',
            'holidays' => 'nullable|string|max:5000',
        ], [
            'working_days.min' => 'Pick at least one working day.',
        ]);

        if (Carbon::parse($request->office_close_time)->lte(Carbon::parse($request->office_open_time))) {
            return response()->json(['message' => 'Office closing time must be after opening time.', 'errors' => ['office_close_time' => ['Must be after opening time.']]], 422);
        }

        // Holidays: comma / newline separated dates; invalid entries are rejected, not silently kept.
        $holidays = [];
        $bad = [];
        foreach (preg_split('/[\s,]+/', (string) $request->holidays, -1, PREG_SPLIT_NO_EMPTY) as $h) {
            $d = \DateTime::createFromFormat('Y-m-d', $h);
            $d && $d->format('Y-m-d') === $h ? $holidays[] = $h : $bad[] = $h;
        }
        if ($bad) {
            return response()->json(['message' => 'Invalid holiday date(s): '.implode(', ', $bad).'. Use YYYY-MM-DD.', 'errors' => ['holidays' => ['Invalid dates.']]], 422);
        }
        $holidays = array_values(array_unique($holidays));
        sort($holidays);

        $settings = GeneralSetting::firstOrCreate([]);

        $settings->update([
            'office_open_time' => $request->office_open_time,
            'office_close_time' => $request->office_close_time,
            'late_threshold' => $request->late_threshold,
            'half_day_threshold' => $request->half_day_threshold,
            'break_end_time' => $request->break_end_time,
            'late_deduction_threshold' => $request->late_deduction_threshold,
            'half_day_deduction_threshold' => $request->half_day_deduction_threshold,
            'early_out_threshold' => $request->early_out_threshold,
            'early_punch_in_minutes' => $request->input('early_punch_in_minutes', $settings->early_punch_in_minutes ?? 15),
            'working_days' => $request->has('working_days') ? array_values(array_map('intval', $request->working_days)) : ($settings->working_days ?: [1, 2, 3, 4, 5]),
            'holidays' => $holidays,
        ]);

        AttendancePolicy::refresh();
        Log::info('Attendance settings changed', ['by' => Auth::id()]);

        return response()->json(['success' => 'Settings updated successfully!']);
    }
}
