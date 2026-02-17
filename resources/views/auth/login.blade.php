<x-guest-layout>
    <style>
        .transition-all {
            transition: all 0.25s ease-in-out;
        }

        .focus-ring-success:focus {
            border-color: #198754 !important;
            box-shadow: 0 0 0 4px rgba(25, 135, 84, 0.1) !important;
            outline: none;
        }

        .alert-subtle-success {
            background-color: #f0fff4;
            color: #198754;
        }

        .alert-subtle-danger {
            background-color: #fff5f5;
            color: #e53e3e;
        }

        .hover-underline:hover {
            text-decoration: underline !important;
        }

        .btn-success {
            background: linear-gradient(135deg, #198754 0%, #a8e323 100%);
            border: none;
        }

        .btn-success:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 15px rgba(25, 135, 84, 0.2);
        }

        .shadow-success-sm {
            box-shadow: 0 4px 6px -1px rgba(25, 135, 84, 0.1), 0 2px 4px -1px rgba(25, 135, 84, 0.06);
        }
    </style>
    <div class="max-w-464-px mx-auto w-100 p-4">
        <div class="text-start mb-40">
            <h5 class="fw-bold text-dark-main mb-2" style="letter-spacing: -0.5px;">Sign In to 10X One</h3>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />
        @if (session('success'))
            <div class="alert alert-subtle-success d-flex align-items-center radius-12 border-0 shadow-sm mb-4">
                <iconify-icon icon="lucide:check-circle" class="me-2 fs-5"></iconify-icon>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-subtle-danger d-flex align-items-center radius-12 border-0 shadow-sm mb-4">
                <iconify-icon icon="lucide:alert-circle" class="me-2 fs-5"></iconify-icon>
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf

            <div class="mb-24">
                <label for="email" class="form-label fw-semibold text-sm text-dark-main mb-2">Email Address</label>
                <div class="position-relative">
                    <div class="position-absolute top-50 start-0 translate-middle-y ps-3 text-secondary-light">
                        <iconify-icon icon="solar:letter-linear" class="fs-5"></iconify-icon>
                    </div>
                    <input id="email"
                        class="form-control h-56-px bg-white border-neutral-200 radius-12 ps-5 focus-ring-success transition-all"
                        type="email" name="email" placeholder="name@company.com" value="{{ old('email') }}"
                        required autofocus />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs fw-medium text-danger" />
            </div>

            <div class="mb-20">
                <label for="password" class="form-label fw-semibold text-sm text-dark-main mb-2">Password</label>
                <div class="position-relative">
                    <div class="position-absolute top-50 start-0 translate-middle-y ps-3 text-secondary-light">
                        <iconify-icon icon="solar:lock-password-linear" class="fs-5"></iconify-icon>
                    </div>
                    <input id="password"
                        class="form-control h-56-px bg-white border-neutral-200 radius-12 ps-5 focus-ring-success transition-all"
                        type="password" name="password" placeholder="••••••••" required
                        autocomplete="current-password" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs fw-medium text-danger" />
            </div>

            <div class="d-flex justify-content-between align-items-center mb-32">
                <div class="form-check custom-check d-flex align-items-center">
                    <input class="form-check-input border-neutral-300 pointer" type="checkbox" name="remember"
                        id="remember">
                    <label class="form-check-label text-sm text-secondary-light pointer ms-2" for="remember">
                        Keep me logged in
                    </label>
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-success-600 text-sm fw-semibold text-decoration-none hover-underline">
                        Forgot Password?
                    </a>
                @endif
            </div>

            <button type="submit"
                class="btn btn-success w-100 radius-12 py-16 fw-bold shadow-success-sm transition-all d-flex align-items-center justify-content-center gap-2">
                <span>Sign In</span>
                <iconify-icon icon="solar:alt-arrow-right-linear" class="fs-5"></iconify-icon>
            </button>
        </form>

        <div class="mt-40 text-center">
            <p class="text-sm text-secondary-light">Don't have an account? <a href="#"
                    class="text-success-600 fw-bold text-decoration-none">Contact Admin</a></p>
        </div>
    </div>
</x-guest-layout>
