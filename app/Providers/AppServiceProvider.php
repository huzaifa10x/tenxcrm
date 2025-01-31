<?php

namespace App\Providers;

use App\Http\Middleware\CheckPermission;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::aliasMiddleware('permission', CheckPermission::class);
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $permissions = $user->roles->flatMap->permissions->pluck('name')->unique();
                $view->with('userPermissions', $permissions);
            }
        });
    }
}
