@extends('layouts.app')

@section('title', 'Tiket Saya - AmikomHub')

@push('styles')
<style>
    /* ── PRINT STYLES: sembunyikan semua kecuali tiket yang dicetak ── */
    @media print {
        nav, footer, main > div:first-of-type, #reviewModal, .no-print {
            display: none !important;
        }
        body {
            background: white !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        #printTicketModal {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            height: auto !important;
            background: white !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important;
            margin: 0 !important;
            z-index: 99999 !important;
            box-shadow: none !important;
        }
        #printTicketCard {
            box-shadow: none !important;
            border: 1.5px solid #e5e7eb !important;
            border-radius: 24px !important;
            page-break-inside: avoid !important;
            width: 100% !important;
            max-width: 650px !important;
            margin: 0 auto !important;
        }
    }

    /* ── MODAL TIKET RESPONSIVE ── */
    #pt-modal-body {
        display: flex;
        flex-direction: column;
        gap: 1.75rem;
        padding: 1.75rem 1.5rem;
    }
    #pt-qr-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding-bottom: 1.75rem;
        border-bottom: 1.5px dashed #e2e8f0;
        order: 1;
    }
    #pt-info-section {
        flex: 1;
        order: 2;
    }
    /* Desktop: 2 kolom side-by-side */
    @media (min-width: 640px) {
        #pt-modal-body {
            flex-direction: row;
            gap: 2.25rem;
            padding: 2.25rem 2rem;
        }
        #pt-qr-section {
            padding-bottom: 0;
            border-bottom: none;
            border-left: 1.5px dashed #e2e8f0;
            padding-left: 2.25rem;
            order: 2;
        }
        #pt-info-section {
            order: 1;
        }
    }
</style>
@endpush

@section('content')
    <div class="bg-neutral-50 text-neutral-800 min-h-[calc(100vh-64px)] py-8 sm:py-12 px-4 sm:px-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header Section -->
            <div class="mb-10 text-center md:text-left flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-neutral-950 tracking-tight mb-2">Tiket Saya</h1>
                    <p class="text-neutral-600 text-sm">Temukan e-ticket resmi Anda dan berikan ulasan setelah acara selesai.</p>
                </div>
                <div>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-violet-650 hover:text-violet-850 hover:underline transition">
                        &larr; Cari Event Lainnya
                    </a>
                </div>
            </div>

            <!-- Flash Alert -->
            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl mb-6 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Tickets List -->
            @if($transactions->isEmpty())
                <div class="bg-white border border-neutral-100 rounded-2xl p-12 text-center shadow-sm">
                    <div class="w-16 h-16 bg-violet-50 text-violet-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-12h9.75c1.05 0 2.025.208 2.925.586a10.457 10.457 0 013.314 2.228 10.457 10.457 0 012.228 3.314c.378.9.586 1.875.586 2.925v.75c0 1.05-.208 2.025-.586 2.925a10.457 10.457 0 01-2.228 3.314 10.457 10.457 0 01-3.314 2.228A10.463 10.463 0 0116.5 21h-9.75a1.5 1.5 0 01-1.5-1.5V7.5a1.5 1.5 0 011.5-1.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-neutral-800 mb-2">Belum Ada Tiket Aktif</h3>
                    <p class="text-neutral-500 text-sm max-w-sm mx-auto mb-6">Anda belum membeli tiket untuk acara apa pun yang berhasil dibayar. Jelajahi event menarik dan lakukan reservasi.</p>
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center h-10 px-6 rounded-lg bg-violet-500 hover:bg-violet-600 text-white font-semibold text-sm transition-colors duration-150 shadow-sm">Jelajahi Event</a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($transactions as $trx)
                        @php
                            $event = $trx->event;
                            $eventDate = \Carbon\Carbon::parse($event->date);
                            $reviewAllowedAfter = $eventDate->copy()->addDay();
                            $isAllowedToReview = now()->greaterThanOrEqualTo($reviewAllowedAfter);
                            $hasReviewed = in_array($event->id, $reviewedEventIds);
                            $ticketCode = 'TKT-' . str_pad($trx->id, 8, '0', STR_PAD_LEFT);
                            $isFree = $trx->snap_token === 'FREE_BYPASS' || $trx->total_price == 0;
                        @endphp
                        <div class="bg-white border border-neutral-100 rounded-2xl shadow-sm overflow-hidden flex flex-col md:flex-row">
                            <!-- Left Stub -->
                            <div class="flex-grow p-6 md:p-8 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between mb-4">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-violet-50 text-violet-800">
                                            {{ $event->category->name ?? 'Event' }}
                                        </span>
                                        <span class="text-xs text-neutral-400">Order ID: <strong class="text-neutral-600">{{ $trx->order_id }}</strong></span>
                                    </div>
                                    <h3 class="text-xl font-bold text-neutral-900 tracking-tight mb-2">{{ $event->title }}</h3>

                                    <div class="grid grid-cols-2 gap-4 mt-6">
                                        <div>
                                            <p class="text-neutral-400 text-[10px] font-bold uppercase tracking-wider mb-0.5">Nama Pembeli</p>
                                            <p class="font-semibold text-neutral-800 text-sm">{{ $trx->customer_name }}</p>
                                        </div>
                                        <div>
                                            <p class="text-neutral-400 text-[10px] font-bold uppercase tracking-wider mb-0.5">Tanggal &amp; Waktu</p>
                                            <p class="font-semibold text-neutral-800 text-sm">{{ $eventDate->translatedFormat('d M Y, H:i') }} WIB</p>
                                        </div>
                                        <div>
                                            <p class="text-neutral-400 text-[10px] font-bold uppercase tracking-wider mb-0.5">Lokasi</p>
                                            <p class="font-semibold text-neutral-800 text-sm">{{ $event->location }}</p>
                                        </div>
                                        <div>
                                            <p class="text-neutral-400 text-[10px] font-bold uppercase tracking-wider mb-0.5">Harga Tiket</p>
                                            <p class="font-semibold text-neutral-800 text-sm">
                                                @if($isFree)
                                                    <span class="text-emerald-600">GRATIS</span>
                                                @else
                                                    Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Divider dashed -->
                            <div class="hidden md:flex flex-col items-center justify-between py-4 relative">
                                <div class="w-4 h-4 bg-neutral-50 rounded-full border-b border-r border-neutral-100 -mt-6"></div>
                                <div class="h-full border-l border-dashed border-neutral-200 my-2"></div>
                                <div class="w-4 h-4 bg-neutral-50 rounded-full border-t border-r border-neutral-100 -mb-6"></div>
                            </div>

                            <!-- Right Stub -->
                            <div class="w-full md:w-80 p-6 md:p-8 bg-neutral-50 flex flex-col justify-between items-center text-center border-t md:border-t-0 md:border-l border-neutral-100">
                                <div class="flex flex-col items-center w-full">
                                    <p class="text-neutral-400 text-[10px] font-bold uppercase tracking-wider mb-3">Scan QR untuk Check-in</p>
                                    <div class="w-32 h-32 bg-white p-2 rounded-xl border border-neutral-200 flex items-center justify-center overflow-hidden">
                                        <div id="qr-{{ $trx->id }}" class="w-full h-full flex items-center justify-center"></div>
                                    </div>
                                    <p class="mt-2.5 font-mono font-semibold text-neutral-700 text-xs tracking-wider">{{ $ticketCode }}</p>
                                </div>

                                <!-- Action buttons -->
                                <div class="w-full mt-6 space-y-2">
                                    {{-- Cetak E-Tiket per tiket --}}
                                    <button
                                        onclick="printTicket({
                                            orderId: '{{ $trx->order_id }}',
                                            eventTitle: '{{ addslashes($event->title) }}',
                                            category: '{{ addslashes($event->category->name ?? 'Event') }}',
                                            customerName: '{{ addslashes($trx->customer_name) }}',
                                            customerEmail: '{{ addslashes($trx->customer_email) }}',
                                            eventDate: '{{ $eventDate->translatedFormat('d M Y') }}',
                                            eventTime: '{{ $eventDate->format('H:i') }}',
                                            location: '{{ addslashes($event->location) }}',
                                            price: '{{ $isFree ? 'GRATIS' : 'Rp ' . number_format($trx->total_price, 0, ',', '.') }}',
                                            ticketCode: '{{ $ticketCode }}',
                                            isFree: {{ $isFree ? 'true' : 'false' }}
                                        })"
                                        class="w-full h-9 rounded-lg border border-neutral-200 bg-white hover:bg-neutral-50 text-neutral-800 font-semibold text-xs transition duration-150 flex items-center justify-center gap-1.5 shadow-sm">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                        </svg>
                                        Cetak E-Tiket
                                    </button>

                                    @if($hasReviewed)
                                        <div class="flex flex-col items-center pt-2">
                                            <span class="text-xs text-emerald-600 font-semibold flex items-center gap-1">
                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                                Sudah Diulas
                                            </span>
                                            <a href="{{ route('events.reviews', $event->id) }}" class="text-xs font-semibold text-violet-600 hover:underline transition mt-1">
                                                Lihat Ulasan Penonton &rarr;
                                            </a>
                                        </div>
                                    @elseif($isAllowedToReview)
                                        <button onclick="openReviewModal({{ $event->id }}, '{{ addslashes($event->title) }}')" class="w-full h-9 bg-violet-500 hover:bg-violet-600 text-white rounded-lg font-semibold text-xs transition duration-150 flex items-center justify-center gap-1.5 shadow-sm">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c.198-.625 1.077-.625 1.275 0l2.022 6.388c.089.281.353.475.648.475h6.718c.658 0 .93.84.398 1.235l-5.434 3.947a.3.3 0 00-.108.334l2.022 6.388c.198.625-.529 1.154-1.062.772l-5.434-3.947a.3.3 0 00-.334 0l-5.434 3.947c-.533.382-1.26-.147-1.062-.772l2.022-6.388a.3.3 0 00-.108-.334L1.75 11.597c-.532-.395-.26-1.235.398-1.235h6.718c.295 0 .559-.194.648-.475l2.022-6.388z"></path></svg>
                                            Tulis Ulasan
                                        </button>
                                    @else
                                        <div class="relative group w-full">
                                            <button disabled class="w-full h-9 bg-neutral-150 text-neutral-400 border border-neutral-200 rounded-lg font-semibold text-xs cursor-not-allowed flex items-center justify-center gap-1.5">
                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c.198-.625 1.077-.625 1.275 0l2.022 6.388c.089.281.353.475.648.475h6.718c.658 0 .93.84.398 1.235l-5.434 3.947a.3.3 0 00-.108.334l2.022 6.388c.198.625-.529 1.154-1.062.772l-5.434-3.947a.3.3 0 00-.334 0l-5.434 3.947c-.533.382-1.26-.147-1.062-.772l2.022-6.388a.3.3 0 00-.108-.334L1.75 11.597c-.532-.395-.26-1.235.398-1.235h6.718c.295 0 .559-.194.648-.475l2.022-6.388z"></path></svg>
                                                Tulis Ulasan
                                            </button>
                                            <div class="absolute z-20 bottom-full left-1/2 -translate-x-1/2 mb-2 w-52 bg-neutral-950 text-white text-[11px] font-medium py-1.5 px-3 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none shadow-md text-center">
                                                Ulasan dapat ditulis 1 hari setelah acara selesai
                                                <div class="w-2.5 h-2.5 bg-neutral-950 absolute top-full left-1/2 -translate-x-1/2 -translate-y-1/2 rotate-45"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         PRINT TICKET MODAL — hanya ini yang tercetak saat print
    ══════════════════════════════════════════════════════════════ --}}
    <div id="printTicketModal"
         class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/50 backdrop-blur-sm"
         onclick="closePrintModal(event)">

        <div class="flex min-h-full items-center justify-center p-4 sm:p-6" onclick="closePrintModal(event)">
            <div id="printTicketCard"
                 class="relative bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden"
                 onclick="event.stopPropagation()">

                {{-- Header violet gradient --}}
                <div class="bg-gradient-to-r from-violet-600 to-violet-500 px-8 py-6 flex items-center justify-between">
                    <div>
                        <p class="text-violet-200 text-[10px] font-bold uppercase tracking-widest mb-1">AmikomHub &bull; E-Ticket Resmi</p>
                        <h2 id="pt-eventTitle" class="text-white text-xl font-extrabold leading-tight max-w-xs"></h2>
                        <span id="pt-category" class="mt-2 inline-block bg-white/20 text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full"></span>
                    </div>
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg width="32" height="32" fill="none" stroke="white" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-12h9.75c1.05 0 2.025.208 2.925.586a10.457 10.457 0 013.314 2.228 10.457 10.457 0 012.228 3.314c.378.9.586 1.875.586 2.925v.75c0 1.05-.208 2.025-.586 2.925a10.457 10.457 0 01-2.228 3.314 10.457 10.457 0 01-3.314 2.228A10.463 10.463 0 0116.5 21h-9.75a1.5 1.5 0 01-1.5-1.5V7.5a1.5 1.5 0 011.5-1.5z"/>
                        </svg>
                    </div>
                </div>

                {{-- Perforated divider --}}
                <div class="relative flex items-center">
                    <div class="w-6 h-6 bg-neutral-100 rounded-full -ml-3 flex-shrink-0 border border-neutral-200"></div>
                    <div class="flex-1 border-t-2 border-dashed border-neutral-200 mx-1"></div>
                    <div class="w-6 h-6 bg-neutral-100 rounded-full -mr-3 flex-shrink-0 border border-neutral-200"></div>
                </div>

                {{-- Body --}}
                <div id="pt-modal-body">

                    {{-- QR Section: di mobile tampil ATAS, desktop tampil KANAN --}}
                    <div id="pt-qr-section">
                        <p style="color:#94a3b8;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;text-align:center;">Scan Check-in</p>
                        <div style="width:112px;height:112px;background:#fff;padding:6px;border-radius:12px;border:2px solid #e2e8f0;display:flex;align-items:center;justify-content:center;overflow:hidden;">
                            <div id="pt-qrcode-canvas" style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;"></div>
                        </div>
                        <p id="pt-ticketCode" style="font-family:monospace;font-size:10px;font-weight:700;color:#334155;letter-spacing:.1em;text-align:center;"></p>
                    </div>

                {{-- Info Section: di mobile tampil BAWAH, desktop tampil KIRI --}}
                <div id="pt-info-section">
                    <div class="grid grid-cols-2 gap-x-8 gap-y-5">
                        <div>
                            <p class="text-neutral-400 text-[9px] font-bold uppercase tracking-widest mb-0.5">Nama Pemegang Tiket</p>
                            <p id="pt-name" class="text-neutral-900 font-bold text-sm"></p>
                        </div>
                        <div style="min-width:0;overflow:hidden;">
                            <p class="text-neutral-400 text-[9px] font-bold uppercase tracking-widest mb-0.5">Email</p>
                            <p id="pt-email" class="text-neutral-900 font-semibold text-sm" style="word-break:break-all;"></p>
                        </div>
                        <div>
                            <p class="text-neutral-400 text-[9px] font-bold uppercase tracking-widest mb-0.5">Tanggal</p>
                            <p id="pt-date" class="text-neutral-900 font-bold text-sm"></p>
                        </div>
                        <div>
                            <p class="text-neutral-400 text-[9px] font-bold uppercase tracking-widest mb-0.5">Waktu</p>
                            <p id="pt-time" class="text-neutral-900 font-bold text-sm"></p>
                        </div>
                        <div>
                            <p class="text-neutral-400 text-[9px] font-bold uppercase tracking-widest mb-0.5">Lokasi</p>
                            <p id="pt-location" class="text-neutral-900 font-semibold text-sm"></p>
                        </div>
                        <div>
                            <p class="text-neutral-400 text-[9px] font-bold uppercase tracking-widest mb-0.5">Harga Tiket</p>
                            <p id="pt-price" class="text-neutral-900 font-bold text-sm"></p>
                        </div>
                    </div>
                    <div class="border-t border-dashed border-neutral-200 pt-4" style="margin-top:1.25rem;">
                        <p class="text-neutral-400 text-[9px] font-bold uppercase tracking-widest mb-0.5">Order ID</p>
                        <p id="pt-orderId" class="text-neutral-700 font-mono text-xs"></p>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="bg-neutral-50 border-t border-neutral-100 px-6 sm:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
                <p class="text-neutral-400 text-[10px] font-semibold">
                    Dicetak dari <strong class="text-neutral-600">AmikomHub</strong> &bull; Harap dibawa saat acara berlangsung
                </p>
                <div class="flex items-center gap-2 no-print w-full sm:w-auto justify-center">
                    <button onclick="closePrintModal()"
                        class="h-8 px-4 rounded-lg border border-neutral-200 bg-white hover:bg-neutral-50 text-neutral-700 font-semibold text-xs transition flex-1 sm:flex-initial">
                        Tutup
                    </button>
                    <button onclick="window.print()"
                        class="h-8 px-4 rounded-lg bg-violet-600 hover:bg-violet-700 text-white font-bold text-xs transition flex items-center justify-center gap-1.5 shadow-sm flex-1 sm:flex-initial">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Cetak / Simpan PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Review Modal -->
    <div id="reviewModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-neutral-950/40 backdrop-blur-sm" onclick="closeReviewModal()"></div>
        <div class="relative bg-white w-full max-w-lg mx-4 rounded-2xl shadow-xl border border-neutral-150 overflow-hidden z-10">
            <div class="px-6 py-5 border-b border-neutral-100 flex justify-between items-center bg-neutral-50/50">
                <div>
                    <h3 class="text-base font-bold text-neutral-900" id="modalEventTitle">Tulis Ulasan Event</h3>
                    <p class="text-xs text-neutral-500 mt-0.5">Bagikan pengalaman seru Anda mengikuti acara ini.</p>
                </div>
                <button onclick="closeReviewModal()" class="text-neutral-400 hover:text-neutral-600 transition p-1.5 rounded-lg hover:bg-neutral-100">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form id="reviewForm" class="p-6 space-y-6" onsubmit="submitReview(event)">
                @csrf
                <input type="hidden" name="event_id" id="modalEventId">
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-neutral-800">Penilaian Bintang</label>
                    <div class="flex items-center gap-1.5" id="starContainer">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" onclick="selectRating({{ $i }})" onmouseover="highlightStars({{ $i }})" onmouseout="resetStars()" class="text-neutral-300 hover:scale-110 transition-transform" data-star="{{ $i }}">
                                <svg class="w-8 h-8 stroke-[1.75]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c.198-.625 1.077-.625 1.275 0l2.022 6.388c.089.281.353.475.648.475h6.718c.658 0 .93.84.398 1.235l-5.434 3.947a.3.3 0 00-.108.334l2.022 6.388c.198.625-.529 1.154-1.062.772l-5.434-3.947a.3.3 0 00-.334 0l-5.434 3.947c-.533.382-1.26-.147-1.062-.772l2.022-6.388a.3.3 0 00-.108-.334L1.75 11.597c-.532-.395-.26-1.235.398-1.235h6.718c.295 0 .559-.194.648-.475l2.022-6.388z"></path></svg>
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" required>
                    <p class="text-xs text-rose-500 hidden" id="ratingError">Silakan pilih rating bintang terlebih dahulu.</p>
                </div>
                <div class="space-y-1.5">
                    <div class="flex justify-between items-center">
                        <label for="comment" class="block text-sm font-bold text-neutral-800">Ulasan Tertulis</label>
                        <span class="text-[11px] text-neutral-400 font-medium" id="charCounter">0 / 500</span>
                    </div>
                    <textarea name="comment" id="comment" rows="4" maxlength="500" oninput="updateCharCount(this)" placeholder="Ceritakan keseruan acara..." class="w-full min-h-[96px] p-3 text-sm text-neutral-800 border border-neutral-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-violet-50 focus:border-violet-400 transition-colors resize-none"></textarea>
                </div>
                <div class="pt-2 flex items-center justify-end gap-3 border-t border-neutral-100 -mx-6 -mb-6 p-6 bg-neutral-50/50">
                    <button type="button" onclick="closeReviewModal()" class="h-10 px-5 rounded-lg border border-neutral-200 bg-white hover:bg-neutral-50 text-neutral-700 font-bold text-xs transition">Batal</button>
                    <button type="submit" class="h-10 px-5 bg-violet-500 hover:bg-violet-600 text-white rounded-lg font-bold text-xs transition flex items-center justify-center gap-2 shadow-sm">
                        <span id="submitBtnText">Kirim Ulasan</span>
                        <svg id="submitLoadingSpinner" class="animate-spin h-4 w-4 text-white hidden" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ── PRINT TICKET ──────────────────────────────────────────────────────
        function printTicket(data) {
            document.getElementById('pt-eventTitle').textContent = data.eventTitle;
            document.getElementById('pt-category').textContent  = data.category;
            document.getElementById('pt-name').textContent      = data.customerName;
            document.getElementById('pt-email').textContent     = data.customerEmail;
            document.getElementById('pt-date').textContent      = data.eventDate;
            document.getElementById('pt-time').textContent      = data.eventTime + ' WIB';
            document.getElementById('pt-location').textContent  = data.location;
            document.getElementById('pt-price').textContent     = data.price;
            document.getElementById('pt-orderId').textContent   = data.orderId;
            document.getElementById('pt-ticketCode').textContent = data.ticketCode;

            // Generate real QR code via QRCode.js (client-side)
            generateModalQr(data.ticketCode);

            const priceEl = document.getElementById('pt-price');
            if (data.isFree) {
                priceEl.className = 'text-emerald-600 font-bold text-sm';
            } else {
                priceEl.className = 'text-neutral-900 font-bold text-sm';
            }

            const modal = document.getElementById('printTicketModal');
            modal.classList.remove('hidden');
        }

        function closePrintModal(e) {
            if (e && e.target.closest('#printTicketCard')) return;
            document.getElementById('printTicketModal').classList.add('hidden');
        }

        // Tutup tombol dalam modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('printTicketModal').classList.add('hidden');
                closeReviewModal();
            }
        });

        // ── REVIEW MODAL ──────────────────────────────────────────────────────
        let currentRating = 0;

        function openReviewModal(eventId, eventTitle) {
            document.getElementById('modalEventId').value = eventId;
            document.getElementById('modalEventTitle').textContent = `Tulis Ulasan: ${eventTitle}`;
            document.getElementById('reviewModal').classList.remove('hidden');
            document.getElementById('reviewModal').classList.add('flex');
            currentRating = 0;
            document.getElementById('ratingInput').value = '';
            document.getElementById('comment').value = '';
            document.getElementById('charCounter').textContent = '0 / 500';
            document.getElementById('ratingError').classList.add('hidden');
            resetStars();
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').classList.add('hidden');
            document.getElementById('reviewModal').classList.remove('flex');
        }

        function highlightStars(rating) {
            for (let i = 1; i <= 5; i++) {
                const star = document.querySelector(`[data-star="${i}"]`);
                const svg = star.querySelector('svg');
                if (i <= rating) {
                    svg.setAttribute('fill', '#fbbf24');
                    svg.setAttribute('stroke', '#fbbf24');
                } else {
                    svg.setAttribute('fill', 'none');
                    svg.setAttribute('stroke', 'currentColor');
                }
            }
        }

        function resetStars() { highlightStars(currentRating); }

        function selectRating(rating) {
            currentRating = rating;
            document.getElementById('ratingInput').value = rating;
            document.getElementById('ratingError').classList.add('hidden');
            highlightStars(rating);
        }

        function updateCharCount(textarea) {
            document.getElementById('charCounter').textContent = `${textarea.value.length} / 500`;
        }

        function submitReview(event) {
            event.preventDefault();
            const eventId = document.getElementById('modalEventId').value;
            const rating  = document.getElementById('ratingInput').value;
            const comment = document.getElementById('comment').value;

            if (!rating) {
                document.getElementById('ratingError').classList.remove('hidden');
                return;
            }

            const submitBtnText = document.getElementById('submitBtnText');
            const spinner = document.getElementById('submitLoadingSpinner');
            submitBtnText.textContent = 'Mengirim...';
            spinner.classList.remove('hidden');

            fetch(`/reviews/${eventId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ rating, comment })
            })
            .then(r => r.json().then(data => ({ status: r.status, body: data })))
            .then(res => {
                if (res.status === 200) {
                    alert(res.body.message);
                    closeReviewModal();
                    window.location.reload();
                } else {
                    alert(res.body.message || 'Terjadi kesalahan.');
                    submitBtnText.textContent = 'Kirim Ulasan';
                    spinner.classList.add('hidden');
                }
            })
            .catch(() => {
                alert('Terjadi kesalahan koneksi.');
                submitBtnText.textContent = 'Kirim Ulasan';
                spinner.classList.add('hidden');
            });
        }
    </script>

    {{-- ── QR Code Generation ──────────────────────────────────────────── --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        // Generate a QR code for each ticket card on page load
        @php
            $ticketQrMap = [];
            foreach ($transactions as $t) {
                $ticketQrMap['qr-' . $t->id] = 'TKT-' . str_pad($t->id, 8, '0', STR_PAD_LEFT);
            }
        @endphp
        const ticketQrMap = @json($ticketQrMap);

        document.addEventListener('DOMContentLoaded', function () {
            Object.entries(ticketQrMap).forEach(([elId, code]) => {
                const el = document.getElementById(elId);
                if (el) {
                    new QRCode(el, {
                        text: code,
                        width: 112,
                        height: 112,
                        colorDark: '#1e293b',
                        colorLight: '#ffffff',
                        correctLevel: QRCode.CorrectLevel.M
                    });
                }
            });
        });

        // Generate QR for the print modal (called from printTicket function)
        let _modalQr = null;
        function generateModalQr(ticketCode) {
            const container = document.getElementById('pt-qrcode-canvas');
            container.innerHTML = '';
            _modalQr = new QRCode(container, {
                text: ticketCode,
                width: 100,
                height: 100,
                colorDark: '#1e293b',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.M
            });
        }
    </script>
@endsection