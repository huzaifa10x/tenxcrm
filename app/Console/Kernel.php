<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Register the commands for the application.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('queue:work --tries=3 --timeout=90')->everyMinute()->withoutOverlapping();
        // $schedule->command('assign:monthly-tasks')->monthlyOn(1, '00:00');
    }
}
