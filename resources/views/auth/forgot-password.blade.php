<x-guest-layout>
    <div class="max-w-464-px mx-auto w-100">
        <div>
            <h4 class="mb-12">Forgot Password</h4>
            <p class="mb-32 text-secondary-light text-lg">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </p>
        </div>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="form-control h-56-px bg-neutral-50 radius-12" type="email"
                    name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <button type="submit" class="btn btn-success text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32"
                data-bs-toggle="modal" data-bs-target="#exampleModal">Email Password Reset Link</button>
            <div class="text-center">
                <a href="{{ route('login') }}" class="text-primary-600 fw-bold mt-24">Back to Sign In</a>
            </div>
            {{-- <div class="flex items-center justify-end mt-4">
                <x-primary-button>
                    {{ __('Email Password Reset Link') }}
                </x-primary-button>
            </div> --}}
        </form>
    </div>
</x-guest-layout>
