<?php

use App\Jobs\Xero\XeroFullSyncJob;
use App\Jobs\Xero\XeroIncrementalSyncJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::job(new XeroIncrementalSyncJob)
    ->cron('0 */4 * * *')
    ->withoutOverlapping();

Schedule::job(new XeroFullSyncJob)
    ->dailyAt('02:00')
    ->withoutOverlapping()
    ->onOneServer();

Schedule::call(function () {
    \Illuminate\Support\Facades\Cache::forget('xero_monthly_trend_12');
    \Illuminate\Support\Facades\Cache::forget('xero_prediction_overall');
    \Illuminate\Support\Facades\Cache::forget('xero_month_comparison');
})->daily();

/*
|--------------------------------------------------------------------------
| Task & renewal emails (times are Asia/Dubai — the app timezone)
|--------------------------------------------------------------------------
| Needs the Laravel scheduler cron: * * * * * php artisan schedule:run
*/

// 48h / 24h due-date reminders, overdue notices and escalations.
Schedule::command('tasks:send-due-reminders')
    ->hourlyAt(5)
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/task-emails.log'));

// Daily "My Tasks" digest, working days.
Schedule::command('tasks:send-daily-digest')
    ->weekdays()
    ->at('08:45')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/task-emails.log'));

// Renewal reminders 60 and 30 days out.
Schedule::command('projects:send-renewal-reminders')
    ->dailyAt('09:15')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/task-emails.log'));

