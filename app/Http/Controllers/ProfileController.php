<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update which optional emails the user receives.
     */
    public function updateNotifications(Request $request): RedirectResponse
    {
        $prefs = [];
        foreach (array_keys(\App\Models\User::OPTIONAL_EMAILS) as $key) {
            $prefs[$key] = $request->boolean("notifications.{$key}");
        }

        $request->user()->forceFill(['notification_preferences' => $prefs])->save();

        return Redirect::to(route('profile.edit') . '#email-notifications')->with('status', 'notifications-updated');
    }

    /**
     * Set or change the login PIN. Requires the current password.
     */
    public function updatePin(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePin', [
            'current_password' => ['required', 'current_password'],
            'pin' => ['required', 'string', 'confirmed', function ($attribute, $value, $fail) {
                if ($reason = \App\Services\Auth\PinService::weaknessReason((string) $value)) {
                    $fail($reason);
                }
            }],
        ], [
            'pin.confirmed' => 'The two PINs do not match.',
        ]);

        \App\Services\Auth\PinService::set($request->user(), $validated['pin']);

        return Redirect::to(route('profile.edit') . '#login-pin')->with('status', 'pin-updated');
    }

    /**
     * Remove the login PIN. Requires the current password.
     */
    public function destroyPin(Request $request): RedirectResponse
    {
        $request->validateWithBag('removePin', [
            'current_password' => ['required', 'current_password'],
        ]);

        \App\Services\Auth\PinService::remove($request->user());

        return Redirect::to(route('profile.edit') . '#login-pin')->with('status', 'pin-removed');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
