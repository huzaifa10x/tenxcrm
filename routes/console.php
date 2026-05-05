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
