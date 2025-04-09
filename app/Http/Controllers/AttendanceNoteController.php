<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceNote;
use Illuminate\Http\Request;

class AttendanceNoteController extends Controller
{
    public function store(Request $request, $attendanceId)
    {
        $request->validate([
            'note' => 'required|string|max:255',
        ]);

        $attendance = Attendance::findOrFail($attendanceId);

        // Convert date string to Carbon instance for comparison
        $attendanceDate = \Carbon\Carbon::parse($attendance->date);

        if (!$attendanceDate->isToday()) {
            return response()->json([
                'message' => 'Notes can only be added for today\'s attendance.'
            ], 403);
        }

        $note = AttendanceNote::create([
            'attendance_id' => $attendance->id,
            'user_id' => auth()->id(),
            'note' => $request->note,
        ]);

        return response()->json([
            'note' => [
                'note' => $note->note,
                'created_at' => $note->created_at->format('Y-m-d H:i:s'),
                'user' => [
                    'id' => $note->user->id,
                    'name' => $note->user->name,
                ]
            ],
            'message' => 'Note added successfully.'
        ]);
    }


    // Get all notes for a specific attendance
    public function index($attendanceId)
    {
        $attendance = Attendance::findOrFail($attendanceId);
        $notes = $attendance->notes()->with('user')->get();
        return response()->json([
            'notes' => $notes,
        ]);
    }
}
