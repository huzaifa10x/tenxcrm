<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Register the commands for the application.
     */

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('notifications:check')->everyMinute()->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/notifications.log'));
    }
}
