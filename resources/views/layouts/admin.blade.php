<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel') — AmikomHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Manrope', sans-serif; box-sizing: border-box; }

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
        }

        body { background-color: var(--neutral-50); color: var(--neutral-800); }

        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 8px;
            font-size: 13.5px; font-weight: 600; color: var(--neutral-600);
            text-decoration: none; transition: all 150ms ease-out;
        }
        .nav-item:hover { background: var(--violet-50); color: var(--neutral-800); }
        .nav-item.active { background: var(--violet-50); color: var(--violet-800); }
        .nav-item svg { flex-shrink: 0; }

        .nav-section-label {
            font-size: 10px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.08em; color: var(--neutral-400);
            padding: 0 12px; margin: 16px 0 6px;
        }

        /* Role badge */
        .role-badge-superadmin {
            background: #f3ebfe; color: #5e26ac;
            border: 1px solid #d9c1fb;
        }
        .role-badge-organizer {
            background: #fffbeb; color: #b45309;
            border: 1px solid #fde68a;
        }

        /* Pending count badge */
        .pending-badge {
            background: #fef3c7; color: #b45309;
            font-size: 10px; font-weight: 700;
            padding: 1px 6px; border-radius: 999px;
            margin-left: auto;
        }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--neutral-200); border-radius: 3px; }
    </style>
</head>

<body class="flex min-h-screen">

    <!-- Sidebar -->
    <aside style="width:252px; background:var(--neutral-0); border-right:1px solid var(--neutral-100); flex-shrink:0;"
           class="flex flex-col sticky top-0 h-screen">

        <!-- Logo -->
        <div style="height:64px; border-bottom:1px solid var(--neutral-100); padding:0 20px;"
             class="flex items-center gap-3">
            <div style="width:34px;height:34px;background:var(--violet-500);border-radius:10px;color:#fff;font-weight:700;font-size:13px;"
                 class="flex items-center justify-center flex-shrink-0">AH</div>
            <div>
                <span style="font-size:15px;font-weight:700;color:var(--neutral-950);letter-spacing:-0.02em;display:block;">AmikomHub</span>
                <span style="font-size:10px;font-weight:600;color:var(--neutral-400);">
                    {{ auth()->user()->isSuperadmin() ? 'Admin Panel' : 'Organizer Panel' }}
                </span>
            </div>
        </div>

        <!-- Nav -->
        <nav class="flex-1 px-3 py-3 overflow-y-auto space-y-0.5">

            <!-- Menu Utama (semua role) -->
            <p class="nav-section-label">Menu Utama</p>

            <a href="{{ route('admin.dashboard') }}"
               class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('admin.events.index') }}"
               class="nav-item {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ auth()->user()->isOrganizer() ? 'Event Saya' : 'Kelola Event' }}
            </a>

            <a href="{{ route('admin.transactions.index') }}"
               class="nav-item {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                {{ auth()->user()->isOrganizer() ? 'Transaksi Saya' : 'Laporan Transaksi' }}
            </a>

            {{-- Menu Eksklusif Superadmin --}}
            @if(auth()->user()->isSuperadmin())

                <p class="nav-section-label" style="margin-top:20px;">Kurasi & Moderasi</p>

                @php
                    $pendingCount = \App\Models\Event::pending()->count();
                @endphp

                <a href="{{ route('admin.approvals.index') }}"
                   class="nav-item {{ request()->routeIs('admin.approvals.*') ? 'active' : '' }}">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Antrian Approval
                    @if($pendingCount > 0)
                        <span class="pending-badge">{{ $pendingCount }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.organizers.index') }}"
                   class="nav-item {{ request()->routeIs('admin.organizers.*') ? 'active' : '' }}">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Kelola Organizer
                </a>

                <p class="nav-section-label" style="margin-top:20px;">Konfigurasi</p>

                <a href="{{ route('admin.categories.index') }}"
                   class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Kategori
                </a>

                <a href="{{ route('admin.partners.index') }}"
                   class="nav-item {{ request()->routeIs('admin.partners.*') ? 'active' : '' }}">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5.356-3.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a4 4 0 015.356-3.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Partner
                </a>

            @endif

        </nav>

        <!-- User Info + Logout -->
        <div style="padding:12px 16px; border-top:1px solid var(--neutral-100);">
            <!-- User Card -->
            <div style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;background:var(--neutral-50);margin-bottom:8px;">
                <div style="width:34px;height:34px;border-radius:999px;background:var(--violet-100);color:var(--violet-800);font-size:12px;font-weight:700;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                    {{ auth()->user()->initials }}
                </div>
                <div style="min-width:0;flex:1;">
                    <p style="font-size:13px;font-weight:700;color:var(--neutral-800);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ auth()->user()->name }}
                    </p>
                    <span style="font-size:10px;font-weight:700;padding:1px 7px;border-radius:999px;display:inline-block;margin-top:2px;"
                          class="role-badge-{{ auth()->user()->role === 'superadmin' ? 'superadmin' : 'organizer' }}">
                        {{ auth()->user()->isSuperadmin() ? 'Superadmin' : 'Organizer' }}
                    </span>
                </div>
            </div>

            <!-- Logout -->
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit"
                    style="width:100%;display:flex;align-items:center;gap:9px;padding:8px 12px;border-radius:8px;
                           font-size:13px;font-weight:600;color:var(--neutral-600);background:transparent;border:none;cursor:pointer;
                           transition:all 150ms ease-out;text-align:left;"
                    onmouseover="this.style.background='#fff1f2';this.style.color='#e11d48';"
                    onmouseout="this.style.background='transparent';this.style.color='var(--neutral-600)';">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto" style="padding:36px 40px; background:var(--neutral-50); min-height:100vh;">

        {{-- Flash messages --}}
        @if(session('success'))
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:12px 16px;margin-bottom:24px;
                        display:flex;align-items:center;gap:10px;font-size:13px;font-weight:600;color:#15803d;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                {!! session('success') !!}
            </div>
        @endif

        @if(session('error'))
            <div style="background:#fff1f2;border:1px solid #fecdd3;border-radius:10px;padding:12px 16px;margin-bottom:24px;
                        display:flex;align-items:center;gap:10px;font-size:13px;font-weight:600;color:#be123c;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                {!! session('error') !!}
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>