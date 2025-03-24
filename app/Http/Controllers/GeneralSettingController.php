<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralSetting;

class GeneralSettingController extends Controller
{
    public function index()
    {
        $settings = GeneralSetting::first();
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'office_open_time' => 'required',
            'office_close_time' => 'required|after:office_open_time',
            'late_threshold' => 'required|integer',
            'half_day_threshold' => 'required',
            'break_end_time' => 'required',
            'early_out_threshold' => 'required|before:office_close_time',
            'holidays' => 'nullable|string',
        ]);

        $settings = GeneralSetting::firstOrCreate([]);

        $settings->update([
            'office_open_time' => $request->office_open_time,
            'office_close_time' => $request->office_close_time,
            'late_threshold' => $request->late_threshold,
            'half_day_threshold' => $request->half_day_threshold,
            'break_end_time' => $request->break_end_time,
            'early_out_threshold' => $request->early_out_threshold,
            'holidays' => $request->holidays ? explode(',', $request->holidays) : [],
        ]);

        return response()->json(['success' => 'Settings updated successfully!']);
    }
}
