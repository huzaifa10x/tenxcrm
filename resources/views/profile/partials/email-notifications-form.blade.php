<section id="email-notifications">
    <header class="mb-4">
        <h2 class="h5 text-dark">{{ __('Email Notifications') }}</h2>
        <p class="text-muted small">
            {{ __('Choose which optional emails you receive. Task assignment, completion, overdue and renewal emails are always sent.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.notifications.update') }}">
        @csrf
        @method('patch')

        @foreach (\App\Models\User::OPTIONAL_EMAILS as $key => $label)
            <div class="form-check form-switch mb-3">
                <input type="hidden" name="notifications[{{ $key }}]" value="0">
                <input class="form-check-input" type="checkbox" role="switch" value="1"
                       id="notify_{{ $key }}" name="notifications[{{ $key }}]"
                       @checked($user->wantsEmail($key))>
                <label class="form-check-label" for="notify_{{ $key }}">{{ $label }}</label>
            </div>
        @endforeach

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
            @if (session('status') === 'notifications-updated')
                <span class="text-success small">{{ __('Saved.') }}</span>
            @endif
        </div>
    </form>
</section>
