<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\Auth\PinService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        // The account that last signed in on this browser (encrypted cookie), so the
        // PIN tab can greet them and only ask for the PIN.
        $remembered = null;
        if ($email = $request->cookie(PinService::REMEMBER_COOKIE)) {
            $remembered = User::where('email', $email)->first(['id', 'name', 'email', 'img', 'pin_hash']);
        }

        return view('auth.login', [
            'remembered' => $remembered,
            'defaultTab' => old('pin_form') || $request->session()->get('errors')?->hasBag('pin')
                ? 'pin'
                : ($remembered?->hasPin() ? 'pin' : 'password'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))
            ->withCookie(Cookie::make(PinService::REMEMBER_COOKIE, $request->user()->email, PinService::REMEMBER_MINUTES));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
