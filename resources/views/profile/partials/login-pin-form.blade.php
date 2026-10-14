@php
    $pinErrors = $errors->getBag('updatePin');
    $removeErrors = $errors->getBag('removePin');
@endphp

<section id="login-pin">
    <header class="mb-4">
        <h2 class="h5 text-dark d-flex align-items-center gap-2">
            {{ __('Login PIN') }}
            @if ($user->hasPin())
                <span class="badge bg-success" style="font-size: .7rem;">Active</span>
            @endif
        </h2>
        <p class="text-muted small mb-0">
            Sign in with a 6-digit PIN instead of your password. Your PIN is stored encrypted, and PIN sign-in
            locks for 15 minutes after 5 wrong attempts. Your password always keeps working.
        </p>
        @if ($user->hasPin() && $user->pin_set_at)
            <p class="text-muted small mt-2 mb-0">PIN last changed {{ $user->pin_set_at->timezone('Asia/Dubai')->format('d M Y, h:i A') }}.</p>
        @endif
    </header>

    @if (session('status') === 'pin-updated')
        <div class="alert alert-success py-2 small">Your PIN is saved. Choose <strong>PIN</strong> on the sign-in page next time.</div>
    @elseif (session('status') === 'pin-removed')
        <div class="alert alert-secondary py-2 small">Your PIN was removed. Sign in with your password.</div>
    @endif

    <form method="post" action="{{ route('profile.pin.update') }}" autocomplete="off" style="max-width: 420px;">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="pin_current_password" class="form-label">{{ __('Current password') }}</label>
            <input type="password" name="current_password" id="pin_current_password" autocomplete="current-password"
                   class="form-control @if ($pinErrors->has('current_password')) is-invalid @endif" required>
            @if ($pinErrors->has('current_password'))
                <div class="invalid-feedback">{{ $pinErrors->first('current_password') }}</div>
            @endif
        </div>

        <div class="row g-3 mb-3">
            <div class="col-sm-6">
                <label for="pin_new" class="form-label">{{ $user->hasPin() ? __('New PIN') : __('PIN') }}</label>
                <input type="password" name="pin" id="pin_new" inputmode="numeric" pattern="[0-9]{6}" maxlength="6"
                       autocomplete="new-password" placeholder="6 digits"
                       class="form-control text-center fw-semibold @if ($pinErrors->has('pin')) is-invalid @endif"
                       style="letter-spacing: .5em;" required>
            </div>
            <div class="col-sm-6">
                <label for="pin_confirm" class="form-label">{{ __('Confirm PIN') }}</label>
                <input type="password" name="pin_confirmation" id="pin_confirm" inputmode="numeric" pattern="[0-9]{6}" maxlength="6"
                       autocomplete="new-password" placeholder="6 digits"
                       class="form-control text-center fw-semibold @if ($pinErrors->has('pin')) is-invalid @endif"
                       style="letter-spacing: .5em;" required>
            </div>
            @if ($pinErrors->has('pin'))
                <div class="col-12"><div class="text-danger small">{{ $pinErrors->first('pin') }}</div></div>
            @endif
            <div class="col-12">
                <div class="form-text">Avoid easy PINs like 123456, 111111 or your birth year. Don't reuse your bank PIN.</div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">{{ $user->hasPin() ? __('Change PIN') : __('Set PIN') }}</button>
    </form>

    @if ($user->hasPin())
        <details class="mt-4" @if ($removeErrors->any()) open @endif>
            <summary class="small text-muted" style="cursor: pointer;">Remove my PIN</summary>
            <form method="post" action="{{ route('profile.pin.destroy') }}" class="mt-3" style="max-width: 420px;">
                @csrf
                @method('delete')
                <label for="pin_remove_password" class="form-label">{{ __('Current password') }}</label>
                <div class="d-flex gap-2">
                    <input type="password" name="current_password" id="pin_remove_password" autocomplete="current-password"
                           class="form-control @if ($removeErrors->has('current_password')) is-invalid @endif" required>
                    <button type="submit" class="btn btn-outline-danger text-nowrap">Remove PIN</button>
                </div>
                @if ($removeErrors->has('current_password'))
                    <div class="text-danger small mt-1">{{ $removeErrors->first('current_password') }}</div>
                @endif
            </form>
        </details>
    @endif
</section>
