<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        channels: __DIR__ . '/../routes/channels.php',
        api: __DIR__ . '/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'tasks/*',
            'store-lead/*'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Never put PINs into the flashed "old input" after a failed form.
        $exceptions->dontFlash(['current_password', 'password', 'password_confirmation', 'pin', 'pin_confirmation']);

        // A login form left open until its session expired fails with "419 Page Expired".
        // Send the user back to a fresh login page with a clear message instead.
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            if ($e->getStatusCode() === 419 && $request->is('login', 'login/*') && !$request->expectsJson()) {
                return redirect()->route('login')
                    ->withInput($request->only('email'))
                    ->with('error', 'Your sign-in page had expired. Please try again.');
            }
        });
    })->create();
