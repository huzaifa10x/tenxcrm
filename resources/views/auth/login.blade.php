<x-guest-layout>
    <div class="max-w-464-px mx-auto w-100">
        <div>
            <a href="index.html" class="mb-40 max-w-290-px">
                <img src="assets/images/logo.png" alt="">
            </a>
            <h4 class="mb-12">Sign In to your 10X CRM NEW</h4>
            <p class="mb-32 text-secondary-light text-lg">Welcome back! please enter your detail</p>
        </div>
        <x-auth-session-status class="mb-4" :status="session('status')" />
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="form-control h-56-px bg-neutral-50 radius-12" type="email"
                    name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />

                <x-text-input id="password" class="form-control h-56-px bg-neutral-50 radius-12" type="password"
                    name="password" required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-3">
                <div class="d-flex justify-content-between gap-2">
                    <div class="form-check style-check d-flex align-items-center">
                        <input class="form-check-input border border-neutral-300" type="checkbox" name="remember"
                            value="" id="remeber">
                        <label class="form-check-label" for="remeber">Remember me </label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-primary-600 fw-medium">Forgot
                            Password?</a>
                    @endif
                </div>
            </div>

            <button type="submit" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32"> Sign
                In</button>
        </form>
    </div>
</x-guest-layout>
