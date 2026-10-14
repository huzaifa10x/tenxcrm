<?php

namespace App\Console\Commands;

use App\Services\Attendance\AttendanceService;
use Illuminate\Console\Command;

/**
 * Closes finished attendance days: missed punch-outs are closed at office closing time
 * (flagged auto_closed) and tracked employees with no record on a working day are marked
 * absent, or "leave" when they have approved leave. Idempotent; also runs lazily from the
 * dashboard and attendance pages.
 */
class CloseAttendanceDays extends Command
{
    protected $signature = 'attendance:close-days {--date= : Close one specific date (Y-m-d) instead of catching up}';

    protected $description = 'Close missed punch-outs and record absences for finished working days';

    public function handle(AttendanceService $service): int
    {
        if ($date = $this->option('date')) {
            if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || $date >= now()->toDateString()) {
                $this->error('Give a past date as Y-m-d.');

                return self::FAILURE;
            }
            $n = $service->closeDay($date);
        } else {
            $n = $service->closeThrough();
        }

        $this->info("Attendance closed: {$n} row(s) created or closed.");

        return self::SUCCESS;
    }
}
