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
    <nav class="h-[72px] sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-neutral-100 px-6 flex justify-between items-center transition-all duration-150">
        <div class="max-w-7xl w-full mx-auto flex justify-between items-center">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 decoration-none">
                <div class="w-9 h-9 bg-violet-500 rounded-[10px] flex items-center justify-center text-white font-bold text-sm leading-none flex-shrink-0">
                    AH
                </div>
                <span class="text-[15px] font-bold tracking-tight text-neutral-900 leading-none">AmikomHub</span>
            </a>

            <!-- Menu Links -->
            <div class="flex items-center gap-8 font-semibold text-sm">
                <a href="{{ route('home') }}#events" class="text-neutral-600 hover:text-violet-600 transition-colors duration-150">Jelajahi</a>
                <a href="{{ route('home') }}#categories" class="text-neutral-600 hover:text-violet-600 transition-colors duration-150">Kategori</a>
                <a href="{{ route('ticket') }}" class="text-neutral-600 hover:text-violet-600 transition-colors duration-150 flex items-center gap-1.5">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="inline">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                    Tiket Saya
                </a>
                
                @auth
                    <div class="flex items-center gap-4 pl-4 border-l border-neutral-200">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-violet-100 text-violet-800 text-xs font-bold flex items-center justify-center">
                                {{ auth()->user()->initials }}
                            </div>
                            <span class="text-neutral-800 text-xs font-bold max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                        </div>
                        <form action="{{ route('user.logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-neutral-200 rounded-xl text-neutral-600 hover:text-rose-600 hover:border-rose-100 hover:bg-rose-50 text-xs font-bold transition-all duration-150 cursor-pointer">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" class="mr-1.5">
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
        </div>
    </nav>

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