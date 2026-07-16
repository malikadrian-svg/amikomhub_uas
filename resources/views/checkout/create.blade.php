@extends('layouts.app')

@section('title', 'Checkout - ' . $event->title)

@section('content')
    <main class="max-w-6xl mx-auto px-6 py-12">

        {{-- Breadcrumb --}}
        <div class="mb-8">
            <a href="{{ route('events.show', $event->id) }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-neutral-400 hover:text-violet-600 transition-colors duration-150 group">
                <svg class="w-3.5 h-3.5 transition-transform duration-150 group-hover:-translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7l-7-7 7-7"/>
                </svg>
                Kembali ke Detail Event
            </a>
        </div>

        {{-- Page Header --}}
        <div class="mb-10 space-y-2">
            <h1 class="text-3xl font-extrabold text-neutral-900 leading-tight">Selesaikan Pemesanan</h1>
            <p class="text-neutral-505 text-sm font-medium">Lengkapi rincian formulir data diri Anda untuk penerbitan E-Ticket.</p>
        </div>

        {{-- Error Alert --}}
        @if(session('error'))
            <div class="mb-8 p-4 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold rounded-xl flex items-center gap-2.5">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" class="flex-shrink-0" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            {{-- LEFT: Form Card --}}
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-2xl border border-neutral-200 p-8 shadow-sm">
                    <h3 class="text-base font-extrabold text-neutral-800 flex items-center gap-2 mb-1">
                        <span class="w-6 h-6 bg-violet-50 text-violet-600 rounded-lg flex items-center justify-center text-xs">👤</span>
                        Informasi Kontak Pemesan
                    </h3>
                    <p class="text-neutral-400 text-xs font-medium mb-8">E-Ticket resmi Anda akan dikirimkan langsung ke email di bawah.</p>

                    <form action="{{ route('checkout.store', $event->id) }}" method="POST" class="space-y-6">
                        @csrf

                        {{-- Nama Lengkap --}}
                        <div>
                            <label class="block text-xs font-bold text-neutral-700 mb-2 uppercase tracking-wider">Nama Lengkap</label>
                            <input type="text" name="customer_name" placeholder="Masukkan nama lengkap Anda"
                                class="w-full h-11 px-4 bg-white border border-neutral-200 rounded-xl focus:ring-4 focus:ring-violet-500/10 focus:border-violet-400 outline-none transition font-semibold text-sm text-neutral-805"
                                required value="{{ old('customer_name', auth()->user()->name ?? '') }}">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Email --}}
                            <div>
                                <label class="block text-xs font-bold text-neutral-700 mb-2 uppercase tracking-wider">Alamat Email</label>
                                <input type="email" name="customer_email" placeholder="contoh@alamat.com"
                                    class="w-full h-11 px-4 bg-neutral-50 border border-neutral-200 rounded-xl font-semibold text-sm text-neutral-805 cursor-not-allowed"
                                    readonly value="{{ auth()->user()->email ?? old('customer_email') }}">
                                <p class="text-[10px] text-neutral-400 mt-2 flex items-center gap-1.5 font-semibold">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Email akun Anda — digunakan untuk pengiriman E-Ticket
                                </p>
                            </div>

                            {{-- No. WhatsApp --}}
                            <div>
                                <label class="block text-xs font-bold text-neutral-700 mb-2 uppercase tracking-wider">No. WhatsApp / HP</label>
                                <input type="tel" name="customer_phone" placeholder="08xxxxxxxxxx"
                                    class="w-full h-11 px-4 bg-white border border-neutral-200 rounded-xl focus:ring-4 focus:ring-violet-500/10 focus:border-violet-400 outline-none transition font-semibold text-sm text-neutral-805"
                                    required value="{{ old('customer_phone') }}">
                            </div>
                        </div>

                        {{-- Submit Button (kondisional berdasarkan harga event) --}}
                        <div class="pt-4">
                            @if($event->price == 0)
                                {{-- TOMBOL GRATIS --}}
                                <button type="submit"
                                    class="w-full h-12 bg-violet-600 text-white rounded-xl font-bold text-sm hover:bg-violet-700 transition duration-150 flex items-center justify-center gap-2 shadow-sm">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                    </svg>
                                    Dapatkan Tiket Gratis
                                </button>
                                <p class="text-center text-[10px] text-neutral-400 font-semibold leading-relaxed mt-3">
                                    Pendaftaran Anda gratis. Tiket akan dikirimkan langsung ke email Anda setelah konfirmasi.
                                </p>
                            @else
                                {{-- TOMBOL BAYAR MIDTRANS --}}
                                <button type="submit"
                                    class="w-full h-12 bg-violet-600 text-white rounded-xl font-bold text-sm hover:bg-violet-700 transition duration-150 flex items-center justify-center gap-2 shadow-sm">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Lanjutkan ke Pembayaran
                                </button>
                                <p class="text-center text-[10px] text-neutral-450 font-semibold leading-relaxed mt-3">
                                    Dengan mengklik tombol pembayaran di atas, Anda telah membaca dan menyetujui seluruh <br>
                                    kebijakan pemesanan, reservasi tiket, dan syarat ketentuan AmikomEventHub.
                                </p>
                            @endif
                        </div>

                    </form>
                </div>
            </div>

            {{-- RIGHT: Order Summary Sidebar --}}
            <div class="lg:col-span-1">
                <div class="sticky top-28 space-y-6">

                    {{-- Event Summary Card --}}
                    <div class="bg-white rounded-2xl border border-neutral-200 p-6 shadow-sm space-y-4">
                        <h3 class="text-xs font-bold text-neutral-400 uppercase tracking-wider border-b border-neutral-100 pb-3">Pesanan Anda</h3>

                        {{-- Poster --}}
                        <div class="rounded-xl overflow-hidden bg-neutral-50 max-h-48 border border-neutral-100">
                            <img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path))
                                ? asset('storage/' . $event->poster_path)
                                : 'https://placehold.co/600x450?text=No+Poster' }}" alt="{{ $event->title }}"
                                class="w-full h-full object-cover aspect-[16/9]">
                        </div>

                        {{-- Event Info --}}
                        <div class="space-y-3">
                            <h4 class="font-extrabold text-sm text-neutral-850 leading-snug truncate" title="{{ $event->title }}">{{ $event->title }}</h4>
                            <div class="space-y-2 text-xs font-semibold text-neutral-500">
                                <p class="flex items-center gap-2">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" class="text-violet-600" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }} WIB
                                </p>
                                <p class="flex items-center gap-2">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" class="text-violet-600" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    </svg>
                                    {{ $event->location }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Price Summary Cards --}}
                    <div class="bg-white rounded-2xl border border-neutral-200 p-6 shadow-sm">
                        <h3 class="text-xs font-bold text-neutral-400 uppercase tracking-wider border-b border-neutral-100 pb-3 mb-4">Rincian Harga</h3>

                        <div class="space-y-4 text-xs font-semibold">
                            <div class="flex justify-between text-neutral-600">
                                <span>1x Tiket Masuk</span>
                                <span class="text-neutral-800">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                            </div>
                            @if($event->price > 0)
                            <div class="flex justify-between text-neutral-600">
                                <span>Pajak &amp; Layanan</span>
                                <span class="text-neutral-800">Rp 5.000</span>
                            </div>
                            @endif
                            <div class="border-t border-dashed border-neutral-200 pt-4 flex justify-between items-center">
                                <span class="text-neutral-800 font-bold">Total Pembayaran</span>
                                @if($event->price == 0)
                                    <span class="text-sm font-black text-emerald-500 flex items-center gap-1.5">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        GRATIS
                                    </span>
                                @else
                                    <span class="text-lg font-black text-violet-600">
                                        Rp {{ number_format($event->price + 5000, 0, ',', '.') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Badges details --}}
                    <div class="bg-neutral-50 rounded-2xl p-5 border border-neutral-200 space-y-3.5">
                        <div class="flex items-start gap-3 text-xs font-semibold text-neutral-600">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" class="text-emerald-500 mt-0.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <span>Transaksi Terenkripsi & Pembayaran Snap Terpercaya</span>
                        </div>
                        <div class="flex items-start gap-3 text-xs font-semibold text-neutral-600">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" class="text-emerald-500 mt-0.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <span>Barcode E-Ticket Diterbitkan Secara Instan</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection