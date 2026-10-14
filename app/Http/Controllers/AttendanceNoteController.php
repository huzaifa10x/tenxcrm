<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceNote;
use App\Support\Access;
use Illuminate\Http\Request;

class AttendanceNoteController extends Controller
{
    /**
     * Employees can add notes to their own record for today (and an open overnight record);
     * attendance managers can add notes to any record.
     */
    public function store(Request $request, $attendanceId)
    {
        $request->validate([
            'note' => 'required|string|max:255',
        ]);

        $attendance = Attendance::findOrFail($attendanceId);
        $user = auth()->user();
        $isManager = Access::manageAttendance($user);

        if (! $isManager) {
            if ((int) $attendance->user_id !== (int) $user->id) {
                return response()->json(['message' => 'You can only add notes to your own attendance.'], 403);
            }

            $date = \Carbon\Carbon::parse($attendance->dateString());
            $openOvernight = $date->isYesterday() && $attendance->punch_in && ! $attendance->punch_out;
            if (! $date->isToday() && ! $openOvernight) {
                return response()->json([
                    'message' => 'Notes can only be added for today\'s attendance.',
                ], 403);
            }
        }

        $note = AttendanceNote::create([
            'attendance_id' => $attendance->id,
            'user_id' => $user->id,
            'note' => $request->note,
        ]);

        return response()->json([
            'note' => [
                'note' => $note->note,
                'created_at' => $note->created_at->format('Y-m-d H:i:s'),
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                ],
            ],
            'message' => 'Note added successfully.',
        ]);
    }

    // Get all notes for a specific attendance (owner or attendance managers only)
    public function index($attendanceId)
    {
        $attendance = Attendance::findOrFail($attendanceId);

        if (! Access::viewAttendanceOf(auth()->user(), (int) $attendance->user_id)) {
            return response()->json(['message' => 'Not allowed.'], 403);
        }

        $notes = $attendance->notes()->with('user:id,name')->get();

        return response()->json([
            'notes' => $notes,
        ]);
    }
}
