<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Services\Auth\PinService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class PinLoginTest extends TestCase
{
    use DatabaseTransactions;

    private function userWithPin(string $pin = '482915'): User
    {
        $user = User::factory()->create();
        PinService::set($user, $pin);

        return $user->fresh();
    }

    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('pin-login-ip|127.0.0.1');
    }

    public function test_user_can_sign_in_with_correct_pin(): void
    {
        $user = $this->userWithPin();

        $this->post('/login/pin', ['email' => $user->email, 'pin' => '482915'])
            ->assertRedirect(route('dashboard'))
            ->assertCookie(PinService::REMEMBER_COOKIE);

        $this->assertAuthenticatedAs($user);
    }

    public function test_pin_is_stored_hashed(): void
    {
        $user = $this->userWithPin();

        $this->assertNotSame('482915', $user->pin_hash);
        $this->assertStringStartsWith('$2y$', $user->pin_hash);
    }

    public function test_wrong_pin_is_rejected_and_counts_attempts(): void
    {
        $user = $this->userWithPin();

        $this->post('/login/pin', ['email' => $user->email, 'pin' => '111222'])
            ->assertSessionHasErrors('pin', null, 'pin');

        $this->assertGuest();
        $this->assertSame(1, $user->fresh()->pin_failed_attempts);
    }

    public function test_unknown_email_gets_the_same_generic_error(): void
    {
        $this->post('/login/pin', ['email' => 'nobody@example.com', 'pin' => '482915'])
            ->assertSessionHasErrors(['pin' => "Email or PIN is incorrect. If you haven't set a PIN yet, sign in with your password."], null, 'pin');

        $this->assertGuest();
    }

    public function test_five_wrong_pins_lock_pin_sign_in_but_not_password(): void
    {
        $user = $this->userWithPin();

        foreach (range(1, PinService::MAX_ATTEMPTS) as $i) {
            RateLimiter::clear('pin-login|' . strtolower($user->email) . '|127.0.0.1');
            $this->post('/login/pin', ['email' => $user->email, 'pin' => '111222']);
        }

        $this->assertTrue($user->fresh()->pinIsLocked());

        // Even the right PIN is refused while locked.
        RateLimiter::clear('pin-login|' . strtolower($user->email) . '|127.0.0.1');
        $this->post('/login/pin', ['email' => $user->email, 'pin' => '482915'])
            ->assertSessionHasErrors('pin', null, 'pin');
        $this->assertGuest();

        // Password sign-in still works.
        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_weak_pins_are_rejected(): void
    {
        foreach (['123456', '654321', '111111', '121212', '112233', '123123', '12345', 'abcdef'] as $pin) {
            $this->assertNotNull(PinService::weaknessReason($pin), "{$pin} should be rejected");
        }

        $this->assertNull(PinService::weaknessReason('482915'));
    }

    public function test_setting_a_pin_requires_current_password(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put('/profile/pin', [
            'current_password' => 'wrong-password',
            'pin' => '482915',
            'pin_confirmation' => '482915',
        ])->assertSessionHasErrors('current_password', null, 'updatePin');

        $this->assertFalse($user->fresh()->hasPin());

        $this->actingAs($user)->put('/profile/pin', [
            'current_password' => 'password',
            'pin' => '482915',
            'pin_confirmation' => '482915',
        ])->assertSessionHasNoErrors();

        $this->assertTrue($user->fresh()->hasPin());
    }

    public function test_pin_is_never_flashed_back_to_the_session(): void
    {
        $user = $this->userWithPin();

        $this->post('/login/pin', ['email' => $user->email, 'pin' => '111222']);

        $this->assertNull(session()->getOldInput('pin'));
    }
}
