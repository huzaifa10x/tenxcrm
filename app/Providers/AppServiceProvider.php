<?php

namespace App\Providers;

use App\Http\Middleware\CheckPermission;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\App;

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
        if (App::runningInConsole()) {
            $this->app->resolving(Schedule::class, function (Schedule $schedule) {
                $schedule->command('tasks:process-repeating')->everyMinute()->withoutOverlapping();
            });
        }
    }
}
