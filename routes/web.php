<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoogleAdsProjectController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SeoCampaignProjectController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('permission:can view users')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/users-grid-view', [UserController::class, 'gridView'])->name('users.gridView');
    });
    Route::middleware('permission:can view companies')->group(function () {
        Route::resource('companies', CompanyController::class);
    });
    Route::middleware('permission:can view customers')->group(function () {
        Route::resource('customers', CustomerController::class);
    });
    Route::middleware('permission:can view roles')->group(function () {
        Route::resource('roles', RoleController::class);
    });
    Route::middleware('permission:can view permissions')->group(function () {
        Route::resource('permissions', PermissionController::class);
    });
    Route::middleware('permission:can view industries')->group(function () {
        Route::resource('industries', IndustryController::class);
    });
    Route::middleware('permission:can view payment methods')->group(function () {
        Route::resource('payment-methods', PaymentMethodController::class);
    });
    Route::middleware('permission:can view projects')->group(function () {
        Route::resource('projects', ProjectController::class);
        Route::get('projects-get-extra-fields', [ProjectController::class, 'getExtraFields'])->name('projects-get-extra-fields');
    });
});


require __DIR__ . '/auth.php';
