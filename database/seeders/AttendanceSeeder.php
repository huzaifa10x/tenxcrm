<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            for ($month = 1; $month <= 12; $month++) {
                $daysInMonth = Carbon::create(2024, $month, 1)->daysInMonth;

                $workingDays = collect(range(1, $daysInMonth))
                    ->filter(function ($day) use ($month) {
                        $date = Carbon::create(2024, $month, $day);
                        return $date->isWeekday();
                    });

                $minDays = 10;
                $maxDays = min(20, $workingDays->count());
                $randomWorkingDays = $workingDays->random(rand($minDays, $maxDays));

                foreach ($randomWorkingDays as $day) {
                    $date = Carbon::create(2024, $month, $day);
                    $punchIn = Carbon::createFromTime(rand(8, 10), rand(0, 59), 0);
                    $punchOut = (clone $punchIn)->addHours(rand(7, 9))->addMinutes(rand(0, 59));

                    Attendance::create([
                        'user_id' => $user->id,
                        'date' => $date->toDateString(),
                        'punch_in' => $punchIn->toTimeString(),
                        'punch_out' => $punchOut->toTimeString(),
                        'total_hours' => $punchOut->diff($punchIn)->format('%H:%I'),
                    ]);
                }
            }
        }
    }
}
