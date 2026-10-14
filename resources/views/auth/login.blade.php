<x-guest-layout>
    @php
        $pinErrors = $errors->getBag('pin');
        $rememberedHasPin = $remembered && $remembered->hasPin();
        $initials = $remembered ? collect(explode(' ', trim($remembered->name)))->filter()->take(2)->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))->implode('') : '';
    @endphp

    <style>
        .x-login h1.x-title { font-size: 1.75rem !important; line-height: 1.2 !important; font-weight: 800; letter-spacing: -.5px; color: #0f1412; margin: 0 0 6px; }
        .x-login .x-sub { color: #66736b; font-size: .92rem; margin-bottom: 28px; }

        .x-tabs { display: grid; grid-template-columns: 1fr 1fr; gap: 4px; background: #f1f4ef; border-radius: 12px; padding: 4px; margin-bottom: 26px; }
        .x-tab { border: 0; background: transparent; border-radius: 9px; padding: 10px 12px; font-weight: 600; font-size: .9rem; color: #66736b; display: flex; align-items: center; justify-content: center; gap: 8px; transition: background .15s, color .15s; }
        .x-tab:hover { color: #0f1412; }
        .x-tab[aria-selected="true"] { background: #fff; color: #0f1412; box-shadow: 0 1px 3px rgba(15, 20, 18, .1); }

        .x-label { font-weight: 600; font-size: .85rem; color: #1f2a24; margin-bottom: 8px; display: block; }
        .x-field { position: relative; }
        .x-field .x-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #8a968e; font-size: 1.05rem; pointer-events: none; }
        .x-input { height: 52px; border-radius: 12px; border: 1px solid #dfe5de; padding-left: 46px; font-size: .95rem; background: #fff; width: 100%; transition: border-color .15s, box-shadow .15s; }
        .x-input:focus { outline: none; border-color: #a9e324; box-shadow: 0 0 0 .22rem rgba(169, 227, 36, .3); }
        .x-input.is-invalid { border-color: #e5484d; }
        .x-toggle-pw { position: absolute; right: 8px; top: 50%; transform: translateY(-50%); border: 0; background: none; color: #8a968e; padding: 8px; font-size: 1.1rem; }
        .x-toggle-pw:hover { color: #0f1412; }

        .x-error { display: flex; gap: 8px; align-items: flex-start; background: #fdecec; color: #b42318; border-radius: 10px; padding: 10px 12px; font-size: .85rem; margin-top: 10px; }
        .x-alert { display: flex; gap: 10px; align-items: center; border-radius: 12px; padding: 12px 14px; font-size: .9rem; margin-bottom: 20px; }
        .x-alert-ok { background: #f2fadf; color: #3f6205; }
        .x-alert-err { background: #fdecec; color: #b42318; }

        .x-submit { height: 52px; border-radius: 12px; width: 100%; border: 0; background: #a9e324; color: #0f1412; font-weight: 700; font-size: 1rem; display: flex; align-items: center; justify-content: center; gap: 8px; transition: background .15s, box-shadow .15s; }
        .x-submit:hover { background: #9ad01c; }
        .x-submit:focus-visible { outline: none; box-shadow: 0 0 0 .25rem rgba(169, 227, 36, .45); }
        .x-submit:disabled { opacity: .7; }

        .x-row { display: flex; justify-content: space-between; align-items: center; margin: 4px 0 24px; gap: 12px; }
        .x-row label { font-size: .85rem; color: #4a564e; display: flex; align-items: center; gap: 8px; cursor: pointer; margin: 0; }
        .x-link { color: #4f7a06; font-weight: 600; font-size: .85rem; text-decoration: none; }
        .x-link:hover { color: #3f6205; text-decoration: underline; }

        .x-account { display: flex; align-items: center; gap: 12px; padding: 12px 14px; border: 1px solid #e3e9e2; border-radius: 12px; margin-bottom: 20px; }
        .x-avatar { width: 40px; height: 40px; border-radius: 50%; background: #0f1412; color: #a9e324; display: grid; place-items: center; font-weight: 700; font-size: .9rem; flex-shrink: 0; overflow: hidden; }
        .x-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .x-account .x-name { font-weight: 700; color: #0f1412; font-size: .95rem; line-height: 1.2; }
        .x-account .x-email { color: #66736b; font-size: .8rem; }

        .x-pin-boxes { display: grid; grid-template-columns: repeat(6, 1fr); gap: 10px; }
        .x-pin-box { height: 58px; text-align: center; font-size: 1.5rem; font-weight: 700; border-radius: 12px; border: 1px solid #dfe5de; background: #fff; width: 100%; caret-color: #4f7a06; transition: border-color .15s, box-shadow .15s, background .15s; -webkit-text-security: disc; }
        .x-pin-box:focus { outline: none; border-color: #a9e324; box-shadow: 0 0 0 .22rem rgba(169, 227, 36, .3); }
        .x-pin-box.filled { background: #f7fbec; border-color: #c9e88a; }
        .x-pin-boxes.shake { animation: x-shake .35s; }
        .x-pin-boxes.is-invalid .x-pin-box { border-color: #e5484d; }
        @keyframes x-shake { 20%, 60% { transform: translateX(-6px); } 40%, 80% { transform: translateX(6px); } }

        .x-help { font-size: .82rem; color: #66736b; margin-top: 18px; text-align: center; line-height: 1.5; }
        .x-foot { margin-top: 32px; text-align: center; font-size: .85rem; color: #66736b; }
        @media (max-width: 400px) { .x-pin-boxes { gap: 6px; } .x-pin-box { height: 50px; font-size: 1.25rem; } }
    </style>

    <div class="x-login max-w-464-px mx-auto w-100 p-4">
        <h1 class="x-title">Sign in to 10X One</h1>
        <p class="x-sub">Welcome back. Use your password or your 6-digit PIN.</p>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        @if (session('success'))
            <div class="x-alert x-alert-ok"><i class="bi bi-check-circle-fill"></i>{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="x-alert x-alert-err"><i class="bi bi-exclamation-circle-fill"></i>{{ session('error') }}</div>
        @endif

        <div class="x-tabs" role="tablist" aria-label="Sign-in method">
            <button type="button" class="x-tab" role="tab" id="tab-password" aria-controls="panel-password"
                aria-selected="{{ $defaultTab === 'password' ? 'true' : 'false' }}" data-tab="password">
                <i class="bi bi-key"></i> Password
            </button>
            <button type="button" class="x-tab" role="tab" id="tab-pin" aria-controls="panel-pin"
                aria-selected="{{ $defaultTab === 'pin' ? 'true' : 'false' }}" data-tab="pin">
                <i class="bi bi-grid-3x3-gap"></i> PIN
            </button>
        </div>

        {{-- ── Password ─────────────────────────────────────────────── --}}
        <form method="POST" action="{{ route('login') }}" id="panel-password" role="tabpanel" aria-labelledby="tab-password"
            @if ($defaultTab !== 'password') hidden @endif class="x-guard">
            @csrf

            <div class="mb-3">
                <label for="email" class="x-label">Email address</label>
                <div class="x-field">
                    <i class="bi bi-envelope x-icon"></i>
                    <input id="email" class="x-input @if ($errors->has('email')) is-invalid @endif" type="email" name="email"
                        placeholder="name@10xdigital.ae" value="{{ old('email', $remembered?->email) }}"
                        required autocomplete="username" @if ($defaultTab === 'password') autofocus @endif>
                </div>
                @if ($errors->has('email'))
                    <div class="x-error"><i class="bi bi-exclamation-circle"></i><span>{{ $errors->first('email') }}</span></div>
                @endif
            </div>

            <div class="mb-3">
                <label for="password" class="x-label">Password</label>
                <div class="x-field">
                    <i class="bi bi-lock x-icon"></i>
                    <input id="password" class="x-input @if ($errors->has('password')) is-invalid @endif" type="password" name="password"
                        placeholder="Enter your password" required autocomplete="current-password" style="padding-right: 46px;">
                    <button type="button" class="x-toggle-pw" aria-label="Show password" data-toggle-pw="#password"><i class="bi bi-eye"></i></button>
                </div>
                @if ($errors->has('password'))
                    <div class="x-error"><i class="bi bi-exclamation-circle"></i><span>{{ $errors->first('password') }}</span></div>
                @endif
            </div>

            <div class="x-row">
                <label><input class="form-check-input m-0" type="checkbox" name="remember"> Keep me signed in</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="x-link">Forgot password?</a>
                @endif
            </div>

            <button type="submit" class="x-submit"><span>Sign in</span><i class="bi bi-arrow-right"></i></button>
        </form>

        {{-- ── PIN ──────────────────────────────────────────────────── --}}
        <form method="POST" action="{{ route('login.pin') }}" id="panel-pin" role="tabpanel" aria-labelledby="tab-pin"
            @if ($defaultTab !== 'pin') hidden @endif class="x-guard" autocomplete="off">
            @csrf
            <input type="hidden" name="pin_form" value="1">
            <input type="hidden" name="pin" id="pinValue">

            @if ($remembered)
                <div class="x-account">
                    <span class="x-avatar">
                        @if ($remembered->img)
                            <img src="{{ asset('/public/storage/' . $remembered->img) }}" alt="">
                        @else
                            {{ $initials }}
                        @endif
                    </span>
                    <div class="flex-grow-1 min-w-0">
                        <div class="x-name">{{ $remembered->name }}</div>
                        <div class="x-email text-truncate">{{ $remembered->email }}</div>
                    </div>
                    <a href="{{ route('login.forget') }}" class="x-link">Not you?</a>
                </div>
                <input type="hidden" name="email" value="{{ $remembered->email }}">
            @else
                <div class="mb-3">
                    <label for="pin-email" class="x-label">Email address</label>
                    <div class="x-field">
                        <i class="bi bi-envelope x-icon"></i>
                        <input id="pin-email" class="x-input" type="email" name="email" placeholder="name@10xdigital.ae"
                            value="{{ old('email') }}" required autocomplete="username">
                    </div>
                </div>
            @endif

            <label class="x-label" id="pin-label">6-digit PIN</label>
            <div class="x-pin-boxes @if ($pinErrors->any()) is-invalid shake @endif" role="group" aria-labelledby="pin-label">
                @for ($i = 0; $i < 6; $i++)
                    <input class="x-pin-box" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1"
                        autocomplete="off" aria-label="PIN digit {{ $i + 1 }}" data-pin-index="{{ $i }}">
                @endfor
            </div>
            @if ($pinErrors->any())
                <div class="x-error"><i class="bi bi-exclamation-circle"></i><span>{{ $pinErrors->first() }}</span></div>
            @endif

            <div class="x-row mt-3">
                <label><input class="form-check-input m-0" type="checkbox" name="remember"> Keep me signed in</label>
                <a href="#" class="x-link" data-tab-link="password">Use password instead</a>
            </div>

            <button type="submit" class="x-submit" id="pinSubmit" disabled><span>Sign in with PIN</span><i class="bi bi-arrow-right"></i></button>

            <p class="x-help">
                @if ($remembered && !$rememberedHasPin)
                    You haven't set a PIN yet. Sign in with your password, then go to <strong>My Profile → Login PIN</strong>.
                @else
                    No PIN yet? Sign in with your password once, then set one in <strong>My Profile → Login PIN</strong>.
                @endif
            </p>
        </form>

        <div class="x-foot">Need an account or locked out? Contact your admin.</div>
    </div>

    <script>
        (function () {
            // Tabs
            const tabs = document.querySelectorAll('.x-tab');
            function show(name) {
                tabs.forEach(t => t.setAttribute('aria-selected', String(t.dataset.tab === name)));
                document.getElementById('panel-password').hidden = name !== 'password';
                document.getElementById('panel-pin').hidden = name !== 'pin';
                const focus = name === 'pin'
                    ? (document.getElementById('pin-email') && !document.getElementById('pin-email').value ? document.getElementById('pin-email') : boxes[0])
                    : (document.getElementById('email').value ? document.getElementById('password') : document.getElementById('email'));
                setTimeout(() => focus && focus.focus(), 0);
            }
            tabs.forEach(t => t.addEventListener('click', () => show(t.dataset.tab)));
            document.querySelectorAll('[data-tab-link]').forEach(a => a.addEventListener('click', e => { e.preventDefault(); show(a.dataset.tabLink); }));

            // Show / hide password
            document.querySelectorAll('[data-toggle-pw]').forEach(btn => btn.addEventListener('click', () => {
                const input = document.querySelector(btn.dataset.togglePw);
                const show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.innerHTML = show ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
                btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
            }));

            // PIN boxes: auto-advance, backspace, paste, auto-submit
            const boxes = Array.from(document.querySelectorAll('.x-pin-box'));
            const hidden = document.getElementById('pinValue');
            const submit = document.getElementById('pinSubmit');
            const form = document.getElementById('panel-pin');

            function sync() {
                const pin = boxes.map(b => b.value).join('');
                hidden.value = pin;
                boxes.forEach(b => b.classList.toggle('filled', !!b.value));
                submit.disabled = pin.length !== 6;
                return pin;
            }

            boxes.forEach((box, i) => {
                box.addEventListener('input', () => {
                    const digits = box.value.replace(/\D/g, '');
                    if (digits.length > 1) { fill(digits, i); return; }
                    box.value = digits;
                    if (digits && i < boxes.length - 1) boxes[i + 1].focus();
                    if (sync().length === 6) form.requestSubmit ? form.requestSubmit() : form.submit();
                });
                box.addEventListener('keydown', e => {
                    if (e.key === 'Backspace' && !box.value && i > 0) { boxes[i - 1].value = ''; boxes[i - 1].focus(); sync(); e.preventDefault(); }
                    if (e.key === 'ArrowLeft' && i > 0) { boxes[i - 1].focus(); e.preventDefault(); }
                    if (e.key === 'ArrowRight' && i < boxes.length - 1) { boxes[i + 1].focus(); e.preventDefault(); }
                });
                box.addEventListener('focus', () => box.select());
                box.addEventListener('paste', e => {
                    e.preventDefault();
                    fill((e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, ''), i);
                });
            });

            function fill(digits, start) {
                digits.slice(0, boxes.length - start).split('').forEach((d, k) => boxes[start + k].value = d);
                const next = Math.min(start + digits.length, boxes.length - 1);
                boxes[next].focus();
                if (sync().length === 6) form.requestSubmit ? form.requestSubmit() : form.submit();
            }

            // Prevent double submits and show progress
            document.querySelectorAll('.x-guard').forEach(f => f.addEventListener('submit', e => {
                if (f.dataset.sending) { e.preventDefault(); return; }
                if (f === form && sync().length !== 6) { e.preventDefault(); boxes[0].focus(); return; }
                f.dataset.sending = '1';
                const btn = f.querySelector('.x-submit');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span>Signing in…</span>';
            }));

            // A login page left open for hours has a stale security token; reload it on return.
            const loadedAt = Date.now();
            document.addEventListener('visibilitychange', () => {
                if (document.visibilityState === 'visible' && Date.now() - loadedAt > 60 * 60 * 1000) location.reload();
            });

            sync();
            if (!document.getElementById('panel-pin').hidden) {
                const pe = document.getElementById('pin-email');
                (pe && !pe.value ? pe : boxes[0]).focus();
            }
        })();
    </script>
</x-guest-layout>
