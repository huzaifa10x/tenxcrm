<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SeoCampaignProjectController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('users', UserController::class)->middleware('role:ADMIN');
    Route::resource('companies', CompanyController::class)->middleware('role:ADMIN');
    Route::resource('customers', CustomerController::class)->middleware('role:ADMIN');
    Route::resource('roles', RoleController::class)->middleware('role:ADMIN');
    Route::resource('permissions', PermissionController::class)->middleware('role:ADMIN');
    Route::resource('industries', IndustryController::class)->middleware('role:ADMIN');
    Route::resource('seo-campaign', SeoCampaignProjectController::class);
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';
