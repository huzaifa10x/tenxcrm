<?php

use App\Http\Controllers\Api\ClientLeadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/store-lead', [ClientLeadController::class, 'store']);