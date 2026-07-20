<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — AmikomHub</title>
    <meta name="description" content="Masuk ke AmikomHub untuk memesan tiket event kampus favorit Anda dengan mudah dan cepat.">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --violet-50:  #f3ebfe;
            --violet-100: #d9c1fb;
            --violet-200: #c6a3f9;
            --violet-400: #9d5ef5;
            --violet-500: #8436f2;
            --violet-600: #7831dc;
            --violet-700: #5e26ac;
            --violet-800: #491e85;
            --neutral-0:   #ffffff;
            --neutral-50:  #f8fafc;
            --neutral-100: #f1f5f9;
            --neutral-200: #e2e8f0;
            --neutral-400: #94a3b8;
            --neutral-600: #475569;
            --neutral-800: #1e293b;
            --neutral-950: #0f172a;
            --rose-50: #fff1f2;
            --rose-500: #f43f5e;
            --rose-600: #e11d48;
            --emerald-500: #10b981;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background: var(--neutral-50);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            position: relative;
            overflow-x: hidden;
        }

        /* ── Decorative Background ── */
        .bg-orb {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }
        .bg-orb-1 {
            top: -160px; right: -160px;
            width: 480px; height: 480px;
            background: radial-gradient(circle, #e9d5ff 0%, transparent 70%);
            opacity: 0.55;
        }
        .bg-orb-2 {
            bottom: -160px; left: -160px;
            width: 480px; height: 480px;
            background: radial-gradient(circle, #ede9fe 0%, transparent 70%);
            opacity: 0.45;
        }
        .bg-orb-3 {
            top: 40%; left: 40%;
            width: 200px; height: 200px;
            background: radial-gradient(circle, #ddd6fe 0%, transparent 70%);
            opacity: 0.3;
            transform: translate(-50%, -50%);
        }

        /* ── Layout ── */
        .page-wrap {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
        }

        /* ── Logo / Brand ── */
        .brand {
            text-align: center;
            margin-bottom: 32px;
        }
        .brand-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .brand-icon {
            width: 44px; height: 44px;
            background: var(--violet-500);
            border-radius: 12px;
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(132, 54, 242, 0.25);
            letter-spacing: -0.5px;
        }
        .brand-name {
            font-size: 22px;
            font-weight: 700;
            color: var(--neutral-950);
            letter-spacing: -0.02em;
        }
        .brand-sub {
            font-size: 13px;
            color: var(--neutral-400);
            margin-top: 6px;
            font-weight: 400;
        }

        /* ── Card ── */
        .card {
            background: var(--neutral-0);
            border: 1px solid var(--neutral-100);
            border-radius: 24px;
            padding: 36px 32px;
            box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.04),
                        0 1px 2px -1px rgba(15, 23, 42, 0.04);
        }

        .card-header {
            margin-bottom: 28px;
            text-align: center;
        }
        .card-header h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--neutral-950);
            letter-spacing: -0.02em;
            margin-bottom: 4px;
        }
        .card-header p {
            font-size: 14px;
            color: var(--neutral-600);
            line-height: 1.5;
        }

        /* ── Alerts ── */
        .alert {
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 20px;
            display: flex;
            gap: 8px;
            align-items: flex-start;
        }
        .alert-error {
            background: var(--rose-50);
            border: 1px solid #fecdd3;
        }
        .alert-error ul { list-style: none; display: flex; flex-direction: column; gap: 4px; }
        .alert-error li { font-size: 13px; color: var(--rose-600); display: flex; align-items: center; gap: 6px; }
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
        }
        .alert-success p { font-size: 13px; color: #16a34a; }

        /* ── Form Fields ── */
        .form-group {
            margin-bottom: 16px;
        }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--neutral-800);
            margin-bottom: 6px;
        }
        .form-input {
            width: 100%;
            height: 44px;
            padding: 0 16px;
            border: 1px solid var(--neutral-200);
            border-radius: 12px;
            font-family: 'Manrope', sans-serif;
            font-size: 14px;
            color: var(--neutral-800);
            background: var(--neutral-0);
            outline: none;
            transition: border-color 150ms ease-out, box-shadow 150ms ease-out;
        }
        .form-input::placeholder { color: var(--neutral-400); }
        .form-input:hover { border-color: var(--neutral-400); }
        .form-input:focus {
            border-color: var(--violet-400);
            box-shadow: 0 0 0 4px var(--violet-50);
        }
        .form-input.is-error { border-color: var(--rose-500); }
        .form-input.is-error:focus { box-shadow: 0 0 0 4px #fff1f2; }
        .form-error-msg {
            font-size: 12px;
            color: var(--rose-600);
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* ── Remember Row ── */
        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--neutral-600);
            cursor: pointer;
            user-select: none;
        }
        .checkbox-label input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: var(--violet-500);
            cursor: pointer;
        }
        .text-link {
            font-size: 13px;
            color: var(--violet-500);
            text-decoration: none;
            font-weight: 600;
            transition: color 150ms ease-out;
        }
        .text-link:hover { color: var(--violet-600); }

        /* ── Buttons ── */
        .btn {
            width: 100%;
            height: 44px;
            border-radius: 10px;
            font-family: 'Manrope', sans-serif;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: all 150ms ease-out;
            text-decoration: none;
            border: none;
            letter-spacing: -0.01em;
        }
        .btn-primary {
            background: var(--violet-500);
            color: #fff;
            margin-bottom: 12px;
        }
        .btn-primary:hover { background: var(--violet-600); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(132, 54, 242, 0.3); }
        .btn-primary:active { background: var(--violet-700); transform: translateY(0); box-shadow: none; }

        .btn-google {
            background: var(--neutral-0);
            color: var(--neutral-800);
            border: 1px solid var(--neutral-200);
            margin-bottom: 0;
        }
        .btn-google:hover { background: var(--neutral-50); border-color: var(--neutral-400); }
        .btn-google:active { background: var(--neutral-100); }

        /* ── Divider ── */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
        }
        .divider-line {
            flex: 1;
            height: 1px;
            background: var(--neutral-100);
        }
        .divider-text {
            font-size: 12px;
            color: var(--neutral-400);
            font-weight: 500;
            white-space: nowrap;
        }

        /* ── Footer ── */
        .card-footer {
            text-align: center;
            margin-top: 24px;
        }
        .card-footer p {
            font-size: 13px;
            color: var(--neutral-600);
        }

        .page-footer {
            display: none;
        }

        /* ── Responsive ── */
        @media (max-width: 480px) {
            .card { padding: 28px 20px; border-radius: 20px; }
        }

        /* ── Password visibility toggle ── */
        .input-wrap {
            position: relative;
        }
        .input-wrap .form-input {
            padding-right: 44px;
        }
        .btn-eye {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--neutral-400);
            padding: 4px;
            display: flex;
            transition: color 150ms ease-out;
        }
        .btn-eye:hover { color: var(--neutral-600); }
    </style>
</head>

<body>
    <!-- Decorative background orbs -->
    <div class="bg-orb bg-orb-1" aria-hidden="true"></div>
    <div class="bg-orb bg-orb-2" aria-hidden="true"></div>
    <div class="bg-orb bg-orb-3" aria-hidden="true"></div>

    <div class="page-wrap">

        <!-- Brand -->
        <div class="brand">
            <a href="{{ route('home') }}" class="brand-link">
                <div class="brand-icon">AH</div>
                <span class="brand-name">AmikomHub</span>
            </a>
            <p class="brand-sub">Platform Tiket Event Kampus</p>
        </div>

        <!-- Card -->
        <div class="card">

            <div class="card-header">
                <h1>Selamat Datang</h1>
                <p>Masuk untuk memesan dan mengelola tiket event Anda</p>
            </div>

            {{-- Error Alert --}}
            @if ($errors->any() && !$errors->has('email') && !$errors->has('password'))
                <div class="alert alert-error">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="#e11d48" style="flex-shrink:0;margin-top:1px;">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Success Alert (misal setelah logout) --}}
            @if (session('success'))
                <div class="alert alert-success">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="#16a34a" style="flex-shrink:0;margin-top:1px;">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                    </svg>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            {{-- Error / info dari SSO --}}
            @if (session('error'))
                <div class="alert alert-error">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="#e11d48" style="flex-shrink:0;margin-top:1px;">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <ul><li>{{ session('error') }}</li></ul>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('user.login.post') }}" id="login-form">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-input {{ $errors->has('email') ? 'is-error' : '' }}"
                        placeholder="contoh@email.com"
                        required
                        autofocus
                    >
                    @error('email')
                        <p class="form-error-msg">
                            <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrap">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input {{ $errors->has('password') ? 'is-error' : '' }}"
                            placeholder="Masukkan password Anda"
                            required
                        >
                        <button type="button" class="btn-eye" id="toggle-password" aria-label="Tampilkan password">
                            <svg id="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="form-error-msg">
                            <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember & Forgot -->
                <div class="form-row">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        Ingat saya
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" id="btn-login-submit">
                    Masuk ke Akun
                </button>
            </form>

            <!-- Divider -->
            <div class="divider">
                <div class="divider-line"></div>
                <span class="divider-text">atau masuk dengan</span>
                <div class="divider-line"></div>
            </div>

            <!-- Google SSO Button -->
            <a id="btn-google-login"
               href="{{ route('social.redirect', ['provider' => 'google', 'redirect_to' => url()->previous()]) }}"
               class="btn btn-google">
                <!-- Google SVG Logo -->
                <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.909c1.702-1.567 2.683-3.874 2.683-6.616z" fill="#4285F4"/>
                    <path d="M9 18c2.43 0 4.467-.806 5.956-2.18l-2.909-2.259c-.806.54-1.838.86-3.047.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332C2.438 15.932 5.485 18 9 18z" fill="#34A853"/>
                    <path d="M3.964 10.71c-.18-.54-.282-1.117-.282-1.71s.102-1.17.282-1.71V4.957H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.042l3.007-2.332z" fill="#FBBC05"/>
                    <path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.896 11.426 0 9 0 5.485 0 2.438 2.068.957 5.043l3.007 2.332C4.672 5.164 6.656 3.58 9 3.58z" fill="#EA4335"/>
                </svg>
                Continue with Google
            </a>

            <!-- Register link -->
            <div class="card-footer">
                <p>Belum punya akun? <a href="{{ route('user.register') }}" class="text-link">Daftar sekarang</a></p>
            </div>
        </div>

        <p class="page-footer">&copy; {{ date('Y') }} AmikomHub &mdash; Platform Tiket Event Kampus</p>
    </div>

    <script>
        // Toggle password visibility
        const toggle = document.getElementById('toggle-password');
        const pwInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');

        const eyeOpen = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
        const eyeClosed = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>`;

        toggle.addEventListener('click', () => {
            const isPassword = pwInput.type === 'password';
            pwInput.type = isPassword ? 'text' : 'password';
            eyeIcon.innerHTML = isPassword ? eyeClosed : eyeOpen;
        });
    </script>
</body>

</html>
