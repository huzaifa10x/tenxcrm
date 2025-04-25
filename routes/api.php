<?php

use App\Http\Controllers\Api\ClientLeadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/show-leads', [ClientLeadController::class, 'show'])->name('show-leads');
Route::post('/store-lead', [ClientLeadController::class, 'store'])->name('store-leads');
