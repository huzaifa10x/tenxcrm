<?php

use App\Http\Controllers\Xero\XeroAnalyticsDashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('xero')->name('xero.')->group(function () {

    // OAuth
    Route::get('/connect',  [XeroAnalyticsDashboardController::class, 'connect'])->name('connect');
    Route::get('/callback', [XeroAnalyticsDashboardController::class, 'callback'])->name('callback');

    // Main Dashboard
    Route::get('/analytics', [XeroAnalyticsDashboardController::class, 'dashboard'])->name('analytics.dashboard');

    // AJAX data endpoints
    Route::get('/analytics/summary',    [XeroAnalyticsDashboardController::class, 'summaryAjax'])->name('analytics.summary');
    Route::get('/analytics/dept-trend', [XeroAnalyticsDashboardController::class, 'departmentTrendAjax'])->name('analytics.dept-trend');
    Route::get('/analytics/predict',    [XeroAnalyticsDashboardController::class, 'predictionsAjax'])->name('analytics.predict');
    Route::get('/analytics/client-search', [XeroAnalyticsDashboardController::class, 'clientSearch'])->name('analytics.client-search');
    Route::get('/analytics/clients/{clientId}', [XeroAnalyticsDashboardController::class, 'clientAnalyticsAjax'])->name('analytics.client');

    Route::post('/sync/full',        [XeroAnalyticsDashboardController::class, 'triggerFullSync'])->name('sync.full');
    Route::post('/sync/incremental', [XeroAnalyticsDashboardController::class, 'triggerIncrementalSync'])->name('sync.incremental');
    Route::get('/sync/status',       [XeroAnalyticsDashboardController::class, 'syncStatus'])->name('sync.status');
});
