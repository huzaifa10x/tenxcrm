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
            'holidays' => 'nullable|string'
        ]);

        $settings = GeneralSetting::firstOrCreate([]);
        $settings->update([
            'office_open_time' => $request->office_open_time,
            'office_close_time' => $request->office_close_time,
            'holidays' => explode(',', $request->holidays),
        ]);

        return response()->json(['success' => 'Settings updated successfully!']);
    }
}
