<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * Login PIN rules, hashing and lockout.
 *
 * - The PIN is 6 digits and stored only as a bcrypt hash (like a password).
 * - Obvious PINs (000000, 123456, 112233 …) are rejected.
 * - 5 wrong PINs lock PIN sign-in for 15 minutes; password sign-in still works.
 */
class PinService
{
    public const LENGTH = 6;
    public const MAX_ATTEMPTS = 5;
    public const LOCK_MINUTES = 15;

    /** Cookie that remembers which account last signed in on this browser (email only, encrypted). */
    public const REMEMBER_COOKIE = '10x_last_login';
    public const REMEMBER_MINUTES = 60 * 24 * 180;

    /** Returns an error message if the PIN is not allowed, or null if it is fine. */
    public static function weaknessReason(string $pin): ?string
    {
        if (!preg_match('/^\d{' . self::LENGTH . '}$/', $pin)) {
            return 'Your PIN must be exactly ' . self::LENGTH . ' digits.';
        }

        if (count(array_unique(str_split($pin))) === 1) {
            return 'Your PIN cannot be the same digit repeated.';
        }

        if (str_contains('0123456789012', $pin) || str_contains('9876543210987', $pin)) {
            return 'Your PIN cannot be a sequence like 123456.';
        }

        // Repeating pairs/triples such as 121212, 112233, 123123.
        if (preg_match('/^(\d\d)\1\1$/', $pin) || preg_match('/^(\d{3})\1$/', $pin) || preg_match('/^(\d)\1(\d)\2(\d)\3$/', $pin)) {
            return 'Your PIN is too easy to guess. Avoid repeating patterns.';
        }

        if (in_array($pin, ['696969', '102030', '147258', '159753', '100200', '010203', '090909', '121314', '786786', '786110'], true)) {
            return 'That PIN is too common. Please choose another.';
        }

        return null;
    }

    public static function set(User $user, string $pin): void
    {
        $user->forceFill([
            'pin_hash' => Hash::make($pin),
            'pin_set_at' => now(),
            'pin_failed_attempts' => 0,
            'pin_locked_until' => null,
        ])->save();

        Log::info('Login PIN set', ['user_id' => $user->id]);
    }

    public static function remove(User $user): void
    {
        $user->forceFill([
            'pin_hash' => null,
            'pin_set_at' => null,
            'pin_failed_attempts' => 0,
            'pin_locked_until' => null,
        ])->save();

        Log::info('Login PIN removed', ['user_id' => $user->id]);
    }

    /**
     * Check a PIN, applying the per-account lockout.
     * Returns 'ok', 'wrong' or 'locked'.
     */
    public static function verify(User $user, string $pin): string
    {
        if ($user->pinIsLocked()) {
            return 'locked';
        }

        if ($user->hasPin() && Hash::check($pin, $user->pin_hash)) {
            if (Hash::needsRehash($user->pin_hash)) {
                $user->pin_hash = Hash::make($pin);
            }
            $user->forceFill(['pin_failed_attempts' => 0, 'pin_locked_until' => null])->save();

            return 'ok';
        }

        $attempts = (int) $user->pin_failed_attempts + 1;

        if ($attempts >= self::MAX_ATTEMPTS) {
            $user->forceFill(['pin_failed_attempts' => 0, 'pin_locked_until' => now()->addMinutes(self::LOCK_MINUTES)])->save();
            Log::warning('Login PIN locked after failed attempts', ['user_id' => $user->id, 'ip' => request()->ip()]);

            return 'locked';
        }

        $user->forceFill(['pin_failed_attempts' => $attempts])->save();

        return 'wrong';
    }

    public static function attemptsLeft(User $user): int
    {
        return max(0, self::MAX_ATTEMPTS - (int) $user->pin_failed_attempts);
    }
}
