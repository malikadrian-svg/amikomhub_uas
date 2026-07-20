@extends('layouts.app')

@section('title', 'AmikomHub — Temukan Event Seru!')

@section('content')
    <!-- ===== HERO ===== -->
    <section class="max-w-7xl mx-auto px-5 py-12 md:py-20 flex flex-col lg:flex-row items-center gap-10 lg:gap-16">
        <div class="flex-1 space-y-6 text-center lg:text-left">

            <h1 class="text-4xl sm:text-5xl lg:text-[58px] font-extrabold leading-[1.1] text-neutral-900" style="letter-spacing:-0.02em">
                Temukan &amp; Pesan <br>
                <span class="text-violet-600">Tiket Event</span> Impianmu
            </h1>
            <p class="text-base text-neutral-500 max-w-md mx-auto lg:mx-0 leading-relaxed font-medium">
                Dari konser musik eksklusif hingga workshop teknologi. Pesan dengan aman, instan, dan terpercaya bersama Midtrans.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3">
                <a href="#events"
                    class="group inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-violet-600 text-white rounded-xl font-bold text-sm shadow-md shadow-violet-100/50 hover:bg-violet-700 transition-all duration-150 w-full sm:w-auto">
                    Jelajahi Event
                    <svg class="w-4 h-4 transition-transform duration-150 group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
                <a href="#categories"
                    class="inline-flex items-center justify-center px-5 py-3.5 bg-neutral-50 text-neutral-800 border border-neutral-200/80 rounded-xl font-bold text-sm hover:bg-neutral-100 hover:text-neutral-900 transition-all duration-150 w-full sm:w-auto">
                    Lihat Kategori
                </a>
            </div>
        </div>
        
        <!-- Hero Visual -->
        <div class="flex-1 relative w-full max-w-md lg:max-w-lg hidden sm:block">
            <div class="absolute -top-12 -left-12 w-72 h-72 bg-violet-400 rounded-full filter blur-3xl opacity-10 pointer-events-none"></div>
            <div class="absolute -bottom-12 -right-12 w-64 h-64 bg-fuchsia-400 rounded-full filter blur-3xl opacity-10 pointer-events-none"></div>
            
            <div class="relative z-10 bg-white p-3 rounded-[2rem] border border-neutral-150 shadow-sm">
                <img src="{{ asset('assets/celebration_toast.png') }}" alt="Celebration Toast"
                    class="rounded-[1.5rem] w-full object-cover aspect-[4/3] object-center">
            </div>

            <!-- Floating Badge -->
            <div class="absolute -bottom-5 -left-5 bg-white/95 backdrop-blur-md px-5 py-4 rounded-2xl shadow-sm z-20 border border-neutral-100 flex items-center gap-3">
                <div class="w-9 h-9 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 border border-emerald-100 flex-shrink-0">
                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[9px] text-neutral-400 font-extrabold uppercase tracking-wider">Garansi</p>
                    <p class="font-extrabold text-neutral-800 text-xs leading-tight">Pembayaran Aman Midtrans</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== KATEGORI SECTION ===== -->
    <section id="categories" class="border-t border-neutral-100 bg-neutral-50/60 py-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-12 max-w-2xl mx-auto space-y-2">
                <h2 class="text-3xl font-extrabold text-neutral-900" style="letter-spacing:-0.02em">Kategori Event</h2>
                <p class="text-neutral-500 font-medium text-sm">Jelajahi event berdasarkan tema yang Anda sukai</p>
            </div>
            <div class="flex flex-wrap justify-center gap-4">
                @foreach($categories as $category)
                    @php
                        $cfg = [
                            'bg'     => '#eef2ff', 
                            'color'  => '#4f46e5', 
                            'border' => '#c7d2fe', 
                            'svg'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />'
                        ];
                    @endphp
                    <a href="/?category={{ $category->slug }}#events"
                        class="group flex items-center gap-3 px-5 py-3.5 bg-white rounded-2xl border hover:shadow-md transition-all duration-200 min-w-0"
                        style="border-color: {{ $cfg['border'] }};">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 transition-transform duration-200 group-hover:scale-110"
                            style="background: {{ $cfg['bg'] }}; color: {{ $cfg['color'] }};">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $cfg['svg'] !!}</svg>
                        </div>
                        <span class="font-bold text-sm text-neutral-700 group-hover:text-neutral-900 whitespace-nowrap transition-colors">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===== EVENTS GRID ===== -->
    <section id="events" class="max-w-7xl mx-auto px-6 py-20">
        <!-- Section Header (Centered 1 Column, 2 Rows) -->
        <div class="text-center mb-10 max-w-2xl mx-auto space-y-2">
            <h2 class="text-3xl font-extrabold text-neutral-900" style="letter-spacing:-0.02em">Event Terdekat</h2>
            <p class="text-neutral-500 font-medium text-sm">Jangan sampai kelewatan jajaran acara menarik minggu ini!</p>
        </div>
        
        <!-- Filter Pills Row -->
        <div class="flex flex-wrap justify-center gap-2 mb-10 px-1">
                <a href="/"
                    class="inline-flex items-center px-4 py-1.5 rounded-full font-semibold text-xs transition-all duration-150 border
                    {{ !request()->has('category') || request()->category == ''
                        ? 'bg-violet-600 text-white border-violet-600 shadow-sm'
                        : 'bg-white text-neutral-600 border-neutral-200 hover:border-neutral-300 hover:bg-neutral-50' }}">
                    Semua
                </a>
                @foreach($categories as $cat)
                    <a href="/?category={{ $cat->slug }}#events"
                        class="inline-flex items-center px-4 py-1.5 rounded-full font-semibold text-xs transition-all duration-150 border
                        {{ request()->category == $cat->slug
                            ? 'bg-violet-600 text-white border-violet-600 shadow-sm'
                            : 'bg-white text-neutral-600 border-neutral-200 hover:border-neutral-300 hover:bg-neutral-50' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>

        @if($events->isEmpty())
            <!-- Empty State -->
            <div class="text-center py-24 bg-white rounded-3xl border border-neutral-150">
                <div class="w-16 h-16 bg-neutral-50 border border-neutral-200 rounded-full flex items-center justify-center mx-auto mb-5 text-2xl">
                    🔍
                </div>
                <h3 class="text-base font-bold text-neutral-800 mb-1">Tidak Ada Event Ditemukan</h3>
                @if(request('search'))
                    <p class="text-neutral-500 text-sm">Tidak ada event yang cocok dengan kata kunci "<strong>{{ request('search') }}</strong>".</p>
                @else
                    <p class="text-neutral-500 text-sm">Kategori ini belum memiliki jadwal event saat ini.</p>
                @endif
                <a href="/" class="inline-flex mt-6 px-5 py-2.5 bg-violet-600 text-white rounded-xl font-bold text-xs hover:bg-violet-700 transition">Lihat Semua Event</a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($events as $event)
                    @php
                        $posterPath = $event->poster_path;
                        // Support both old storage/app/public paths and new public/uploads paths
                        $hasPoster  = $posterPath && (
                            file_exists(public_path($posterPath)) ||
                            file_exists(public_path('storage/' . $posterPath))
                        );
                        $posterUrl  = $hasPoster
                            ? (file_exists(public_path($posterPath)) ? asset($posterPath) : asset('storage/' . $posterPath))
                            : null;
                        $isEnded    = \Carbon\Carbon::parse($event->date)->isPast();
                    @endphp
                    <a href="{{ route('events.show', $event->id) }}"
                        class="group bg-white rounded-2xl border {{ $isEnded ? 'border-emerald-500/85 hover:border-emerald-600' : 'border-neutral-200/80 hover:border-violet-200' }} hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col">
                        
                        <!-- Poster Area -->
                        <div class="relative overflow-hidden bg-neutral-100 aspect-[16/10] {{ $isEnded ? 'opacity-75' : '' }}">
                            @if($hasPoster)
                                <img src="{{ $posterUrl }}"
                                    alt="{{ $event->title }}"
                                    class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500">
                            @else
                                <img src="https://placehold.co/600x400?text=No+Poster"
                                    alt="No Poster"
                                    class="w-full h-full object-cover">
                            @endif

                            <!-- Category Badge -->
                            <div class="absolute top-3 left-3">
                                <span class="inline-flex items-center px-2.5 py-1 bg-white/90 text-violet-700 border border-violet-100/80 rounded-lg text-[10px] font-extrabold uppercase tracking-wide backdrop-blur-sm shadow-sm">
                                    {{ $event->category->name }}
                                </span>
                            </div>

                            {{-- Selesai badge: only shown when the event date has passed --}}
                            @if($isEnded)
                                <div class="absolute top-3 right-3">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-600 text-white rounded-lg text-[10px] font-extrabold uppercase tracking-wide shadow-sm">
                                        <svg width="9" height="9" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                                        Selesai
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 flex-grow flex flex-col gap-3">
                            <div class="flex-grow">
                                <h3 class="font-bold text-neutral-900 text-base leading-snug mb-1.5 group-hover:text-violet-600 transition-colors line-clamp-2" style="letter-spacing:-0.02em">
                                    {{ $event->title }}
                                </h3>
                                <div class="flex items-center gap-1.5 text-neutral-500 text-xs font-semibold">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>{{ \Carbon\Carbon::parse($event->date)->translatedFormat('d M Y') }}</span>
                                    <span class="text-neutral-300">·</span>
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>{{ \Carbon\Carbon::parse($event->date)->format('H:i') }} WIB</span>
                                </div>
                            </div>
                            
                            <!-- Price & CTA -->
                            <div class="pt-3 border-t border-neutral-100 flex justify-between items-center">
                                <div>
                                    @if(!$isEnded)
                                        <p class="text-[9px] text-neutral-400 font-extrabold uppercase tracking-wider mb-0.5">Mulai dari</p>
                                        <p class="text-base font-extrabold text-neutral-700">
                                            @if($event->price == 0)
                                                <span>GRATIS</span>
                                            @else
                                                Rp {{ number_format($event->price, 0, ',', '.') }}
                                            @endif
                                        </p>
                                    @endif
                                </div>
                                @if($isEnded)
                                    <span class="text-xs font-bold text-neutral-400 group-hover:text-neutral-600 transition-colors flex items-center gap-1">
                                        Lihat Ulasan
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </span>
                                @else
                                    <span class="text-xs font-bold text-neutral-400 group-hover:text-neutral-600 transition-colors flex items-center gap-1">
                                        Lihat Detail
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>

    <!-- ===== PARTNER SECTION ===== -->
    <section class="border-t border-neutral-100 bg-neutral-50 py-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-12 max-w-2xl mx-auto space-y-2">
                <h2 class="text-3xl font-extrabold text-neutral-900" style="letter-spacing:-0.02em">Partner Kami</h2>
                <p class="text-neutral-500 font-medium text-sm">Didukung oleh ekosistem partner terpercaya</p>
            </div>
            <div class="flex flex-wrap justify-center gap-4">
                @foreach($partners as $partner)
                <div class="bg-white rounded-xl border border-neutral-200 px-5 py-4 flex items-center gap-3 hover:border-violet-200 hover:shadow-sm transition-all duration-150">
                    <div class="w-9 h-9 flex items-center justify-center bg-neutral-50 border border-neutral-100 rounded-lg overflow-hidden flex-shrink-0">
                        <img src="{{ $partner->logo_url ?: 'https://placehold.co/80?text=' . urlencode(substr($partner->name, 0, 2)) }}"
                            alt="{{ $partner->name }}"
                            class="max-w-full max-h-full object-contain">
                    </div>
                    <p class="font-bold text-neutral-700 text-xs whitespace-nowrap">{{ $partner->name }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
