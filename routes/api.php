<?php

use App\Http\Controllers\Api\ClientLeadController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/show-leads', [ClientLeadController::class, 'show'])->name('show-leads');
Route::post('/store-lead', [ClientLeadController::class, 'store'])->name('store-leads');

Route::get('/services', [ServiceController::class, 'getServices']);

Route::post('/login', [ApiAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/me', [ApiAuthController::class, 'me']);
    Route::post('/attendance/punch-in', [AttendanceController::class, 'punchIn']);
    Route::post('/attendance/punch-out', [AttendanceController::class, 'punchOut']);
    Route::get('/attendance/monthly', [AttendanceController::class, 'monthlyAttendance']);
});
