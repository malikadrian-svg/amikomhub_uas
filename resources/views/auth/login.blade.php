<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — AmikomHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Manrope', sans-serif; }
        :root {
            --violet-50:  #f3ebfe;
            --violet-400: #9d5ef5;
            --violet-500: #8436f2;
            --violet-600: #7831dc;
            --neutral-0:   #ffffff;
            --neutral-50:  #f8fafc;
            --neutral-100: #f1f5f9;
            --neutral-200: #e2e8f0;
            --neutral-400: #94a3b8;
            --neutral-600: #475569;
            --neutral-800: #1e293b;
            --neutral-950: #0f172a;
        }
        body { background: var(--neutral-50); }

        .input-field {
            width: 100%; height: 44px; padding: 0 16px;
            border: 1px solid var(--neutral-200); border-radius: 12px;
            font-size: 14px; color: var(--neutral-800);
            background: var(--neutral-0); outline: none;
            transition: border-color 150ms ease-out, box-shadow 150ms ease-out;
        }
        .input-field:hover { border-color: var(--neutral-400); }
        .input-field:focus {
            border-color: var(--violet-400);
            box-shadow: 0 0 0 4px var(--violet-50);
        }
        .input-field::placeholder { color: var(--neutral-400); }

        .btn-primary {
            width: 100%; height: 40px;
            background: var(--violet-500); color: #fff;
            border: none; border-radius: 8px;
            font-size: 14px; font-weight: 600;
            cursor: pointer; transition: background 150ms ease-out;
        }
        .btn-primary:hover { background: var(--violet-600); }
        .btn-primary:active { background: #5e26ac; }
    </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-center px-4 py-12">

    <!-- Decorative background dots -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
        <div style="position:absolute;top:-120px;right:-120px;width:360px;height:360px;
                    background:radial-gradient(circle, #e9d5ff 0%, transparent 70%);opacity:.5;"></div>
        <div style="position:absolute;bottom:-120px;left:-120px;width:360px;height:360px;
                    background:radial-gradient(circle, #ede9fe 0%, transparent 70%);opacity:.5;"></div>
    </div>

    <div class="relative w-full" style="max-width:420px;">

        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-3">
                <div style="width:40px;height:40px;background:var(--violet-500);border-radius:12px;
                            color:#fff;font-weight:700;font-size:14px;"
                     class="flex items-center justify-center shadow-sm">AH</div>
                <span style="font-size:20px;font-weight:700;color:var(--neutral-950);letter-spacing:-0.02em;">
                    AmikomHub
                </span>
            </a>
            <p style="font-size:14px;color:var(--neutral-400);margin-top:8px;">
                Area khusus administrator
            </p>
        </div>

        <!-- Card -->
        <div style="background:var(--neutral-0);border:1px solid var(--neutral-100);border-radius:24px;padding:32px;
                    box-shadow:0 1px 3px 0 rgba(15,23,42,.03),0 1px 2px -1px rgba(15,23,42,.03);">

            <div style="margin-bottom:24px;">
                <h1 style="font-size:24px;font-weight:700;color:var(--neutral-950);letter-spacing:-0.02em;margin-bottom:4px;">
                    Selamat Datang
                </h1>
                <p style="font-size:14px;color:var(--neutral-600);">Masuk untuk mengakses panel admin</p>
            </div>

            {{-- Error Alert --}}
            @if ($errors->any())
                <div style="background:#fff1f2;border:1px solid #fecdd3;border-radius:12px;padding:12px 16px;margin-bottom:20px;">
                    <ul style="list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:4px;">
                        @foreach ($errors->all() as $error)
                            <li style="font-size:13px;color:#e11d48;display:flex;align-items:center;gap:6px;">
                                <svg width="14" height="14" viewBox="0 0 20 20" fill="#e11d48" style="flex-shrink:0;">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                                </svg>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf

                <!-- Email -->
                <div style="margin-bottom:16px;">
                    <label for="email"
                           style="display:block;font-size:13px;font-weight:600;color:var(--neutral-800);margin-bottom:6px;">
                        Email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="input-field" placeholder="admin@amikom.ac.id" required autofocus>
                </div>

                <!-- Password -->
                <div style="margin-bottom:24px;">
                    <label for="password"
                           style="display:block;font-size:13px;font-weight:600;color:var(--neutral-800);margin-bottom:6px;">
                        Password
                    </label>
                    <input type="password" id="password" name="password"
                           class="input-field" placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="btn-primary">Masuk ke Dashboard</button>
            </form>
        </div>

        <p style="text-align:center;font-size:12px;color:var(--neutral-400);margin-top:24px;">
            &copy; {{ date('Y') }} AmikomHub &mdash; Admin Panel
        </p>
    </div>

</body>
</html>
