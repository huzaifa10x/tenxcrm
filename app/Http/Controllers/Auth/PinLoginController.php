<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\PinService;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Sign in with email + 6-digit PIN.
 *
 * Two layers of brute-force protection:
 *  1. Rate limit per email+IP (5/min) and per IP (20/min).
 *  2. Per-account lockout in PinService (5 wrong PINs → 15 min lock).
 */
class PinLoginController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'pin'   => ['required', 'string'],
        ]);

        $email = Str::lower(trim($request->input('email')));
        $pin = preg_replace('/\D/', '', (string) $request->input('pin'));

        $this->ensureNotRateLimited($request, $email);

        $user = User::where('email', $email)->first();

        if (!$user || !$user->hasPin() || strlen($pin) !== PinService::LENGTH) {
            $this->hit($request, $email);
            // Same message whether or not the account exists, so emails can't be probed.
            throw ValidationException::withMessages([
                'pin' => 'Email or PIN is incorrect. If you haven\'t set a PIN yet, sign in with your password.',
            ])->errorBag('pin');
        }

        $result = PinService::verify($user, $pin);

        if ($result === 'locked') {
            $minutes = max(1, (int) ceil(now()->diffInSeconds($user->fresh()->pin_locked_until) / 60));
            throw ValidationException::withMessages([
                'pin' => "Too many wrong PINs. PIN sign-in is locked for {$minutes} minute(s) — you can still sign in with your password.",
            ])->errorBag('pin');
        }

        if ($result === 'wrong') {
            $this->hit($request, $email);
            $left = PinService::attemptsLeft($user->fresh());
            throw ValidationException::withMessages([
                'pin' => "Email or PIN is incorrect. {$left} attempt(s) left before PIN sign-in is locked.",
            ])->errorBag('pin');
        }

        RateLimiter::clear($this->key($request, $email));

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        Log::info('User signed in with PIN', ['user_id' => $user->id, 'ip' => $request->ip()]);

        return redirect()->intended(route('dashboard'))
            ->withCookie(Cookie::make(PinService::REMEMBER_COOKIE, $user->email, PinService::REMEMBER_MINUTES));
    }

    /** "Not you?" — forget the remembered account on this browser. */
    public function forget(): RedirectResponse
    {
        return redirect()->route('login')->withCookie(Cookie::forget(PinService::REMEMBER_COOKIE));
    }

    private function key(Request $request, string $email): string
    {
        return 'pin-login|' . $email . '|' . $request->ip();
    }

    private function hit(Request $request, string $email): void
    {
        RateLimiter::hit($this->key($request, $email), 60);
        RateLimiter::hit('pin-login-ip|' . $request->ip(), 60);
    }

    private function ensureNotRateLimited(Request $request, string $email): void
    {
        foreach ([[$this->key($request, $email), 5], ['pin-login-ip|' . $request->ip(), 20]] as [$key, $max]) {
            if (RateLimiter::tooManyAttempts($key, $max)) {
                event(new Lockout($request));
                $seconds = RateLimiter::availableIn($key);
                throw ValidationException::withMessages([
                    'pin' => "Too many attempts. Please wait {$seconds} seconds and try again.",
                ])->errorBag('pin');
            }
        }
    }
}
