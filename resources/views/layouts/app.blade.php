<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AmikomEventHub - Temukan Event Seru!')</title>
    
    <!-- load Manrope Font -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- load Tailwind with Custom Config -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        violet: {
                            50: '#f3ebfe',
                            100: '#d9c1fb',
                            200: '#c6a3f9',
                            300: '#ad78f6',
                            400: '#9d5ef5',
                            500: '#8436f2',
                            600: '#7831dc',
                            700: '#5e26ac',
                            800: '#491e85',
                            900: '#371766',
                        },
                        neutral: {
                            0: '#ffffff',
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#0b0f19',
                        }
                    },
                    fontFamily: {
                        sans: ['Manrope', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        h1, h2, h3, h4, h5, h6 {
            letter-spacing: -0.02em;
        }
    </style>
    @stack('styles')
</head>

<body class="min-h-screen flex flex-col bg-neutral-50 text-neutral-800 font-sans antialiased">

    <!-- Navigation Header -->
    <nav class="h-[64px] sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-neutral-100 transition-all duration-150">
        <div class="max-w-7xl w-full mx-auto h-full px-5 flex justify-between items-center">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 no-underline flex-shrink-0">
                <div class="w-8 h-8 bg-violet-500 rounded-[9px] flex items-center justify-center text-white font-bold text-sm leading-none flex-shrink-0">
                    AH
                </div>
                <span class="text-[15px] font-bold tracking-tight text-neutral-900 leading-none">AmikomHub</span>
            </a>

            <!-- Desktop Search Bar -->
            <form method="GET" action="{{ route('home') }}" class="hidden md:flex items-center flex-1 max-w-xs mx-6">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <div class="relative w-full">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari event..."
                        class="w-full h-9 pl-9 pr-4 bg-neutral-50 border border-neutral-200 rounded-xl text-sm font-medium text-neutral-800 placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 transition">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                </div>
            </form>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-6 font-semibold text-sm">
                <a href="{{ route('home') }}#events" class="text-neutral-600 hover:text-violet-600 transition-colors duration-150">Event</a>
                <a href="{{ route('home') }}#categories" class="text-neutral-600 hover:text-violet-600 transition-colors duration-150">Kategori</a>
                <a href="{{ route('ticket') }}" class="text-neutral-600 hover:text-violet-600 transition-colors duration-150">Tiket Saya</a>

                @auth
                    <div class="flex items-center gap-3 pl-4 border-l border-neutral-200">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-violet-100 text-violet-800 text-[11px] font-bold flex items-center justify-center flex-shrink-0">
                                {{ auth()->user()->initials }}
                            </div>
                            <span class="text-neutral-800 text-xs font-bold max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                        </div>
                        <form action="{{ route('user.logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 border border-neutral-200 rounded-lg text-neutral-600 hover:text-rose-600 hover:border-rose-100 hover:bg-rose-50 text-xs font-bold transition-all duration-150 cursor-pointer">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" class="mr-1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/>
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('user.login') }}" class="inline-flex items-center justify-center px-4 py-2 bg-violet-600 text-white rounded-xl text-xs font-bold hover:bg-violet-700 transition-colors duration-150">
                        Masuk
                    </a>
                @endauth
            </div>

            <!-- Mobile Hamburger Button -->
            <button id="navMenuBtn" class="md:hidden flex items-center justify-center w-9 h-9 rounded-lg border border-neutral-200 text-neutral-600 hover:bg-neutral-50 transition-colors duration-150" aria-label="Open menu">
                <svg id="hamburgerIcon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.25" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg id="closeIcon" class="hidden" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.25" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

        </div>

        <!-- Mobile Dropdown Menu -->
        <div id="mobileMenu" class="hidden md:hidden absolute top-[64px] left-0 right-0 z-50 bg-white border-b border-neutral-100 shadow-lg px-5 py-4 space-y-1">

            <!-- Mobile Search -->
            <form method="GET" action="{{ route('home') }}" class="mb-3">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari event..."
                        class="w-full h-10 pl-9 pr-4 bg-neutral-50 border border-neutral-200 rounded-xl text-sm font-medium text-neutral-800 placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 transition">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                </div>
            </form>

            <a href="{{ route('home') }}#events" class="flex items-center gap-2 py-2.5 text-sm font-semibold text-neutral-700 hover:text-violet-600 transition-colors" onclick="toggleMobileMenu()">Event</a>
            <a href="{{ route('home') }}#categories" class="flex items-center gap-2 py-2.5 text-sm font-semibold text-neutral-700 hover:text-violet-600 transition-colors" onclick="toggleMobileMenu()">Kategori</a>
            <a href="{{ route('ticket') }}" class="flex items-center gap-2 py-2.5 text-sm font-semibold text-neutral-700 hover:text-violet-600 transition-colors" onclick="toggleMobileMenu()">Tiket Saya</a>

            <div class="border-t border-neutral-100 pt-3 mt-1">
                @auth
                    <div class="flex items-center gap-2.5 mb-3">
                        <div class="w-8 h-8 rounded-full bg-violet-100 text-violet-800 text-xs font-bold flex items-center justify-center flex-shrink-0">
                            {{ auth()->user()->initials }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-neutral-800 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-neutral-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <form action="{{ route('user.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 h-10 border border-rose-200 bg-rose-50 text-rose-600 text-xs font-bold rounded-xl hover:bg-rose-100 transition">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('user.login') }}" class="w-full flex items-center justify-center h-10 bg-violet-600 text-white rounded-xl text-xs font-bold hover:bg-violet-700 transition-colors">
                        Masuk ke Akun
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const hamburger = document.getElementById('hamburgerIcon');
            const closeBtn = document.getElementById('closeIcon');
            const isHidden = menu.classList.contains('hidden');
            menu.classList.toggle('hidden', !isHidden);
            hamburger.classList.toggle('hidden', isHidden);
            closeBtn.classList.toggle('hidden', !isHidden);
        }
        document.getElementById('navMenuBtn').addEventListener('click', toggleMobileMenu);
    </script>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Pinned Footer -->
    <footer class="bg-neutral-950 text-neutral-400 py-16 px-6 mt-auto border-t border-neutral-900">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="space-y-4 col-span-2">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-violet-500 rounded-[10px] flex items-center justify-center text-white font-bold text-sm leading-none flex-shrink-0">
                        AH
                    </div>
                    <span class="text-[15px] font-bold text-white tracking-tight leading-none">AmikomHub</span>
                </div>
                <p class="max-w-xs text-sm text-neutral-500 leading-relaxed">
                    Platform reservasi tiket event online terbaik untuk mahasiswa dan penyelenggara profesional.
                </p>
            </div>
            <div>
                <h4 class="text-white text-sm font-bold tracking-wide uppercase mb-6">Navigasi</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition-colors duration-150">Home</a></li>
                    <li><a href="{{ route('home') }}#events" class="hover:text-white transition-colors duration-150">Semua Event</a></li>
                    <li><a href="{{ route('ticket') }}" class="hover:text-white transition-colors duration-150">Tiket Saya</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white text-sm font-bold tracking-wide uppercase mb-6">Hubungi Kami</h4>
                <ul class="space-y-3 text-sm text-neutral-500">
                    <li>support@eventtiket.com</li>
                    <li>+62 812 3456 7890</li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto pt-8 mt-12 border-t border-neutral-900 text-center text-neutral-600 text-xs">
            &copy; 2024 AmikomEventHub. Built with Laravel & Tailwind CSS.
        </div>
    </footer>

</body>

</html>