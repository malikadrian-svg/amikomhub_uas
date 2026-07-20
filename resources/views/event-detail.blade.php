@extends('layouts.app')

@section('title', $event->title . ' - AmikomEventHub')

@section('content')

{{-- ─── HERO BLOCK ─────────────────────────────────────────────────────────── --}}
<section class="w-full bg-neutral-50 border-b border-neutral-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 pt-8 pb-0">

        {{-- Back breadcrumb --}}
        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-1.5 text-xs font-semibold text-neutral-400 hover:text-violet-600 transition-colors duration-150 mb-10 group">
            <svg class="w-3.5 h-3.5 transition-transform duration-150 group-hover:-translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7l-7-7 7-7"/>
            </svg>
            Jelajahi Event
        </a>

        {{-- Category + Title row --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-end pb-12">

            {{-- Title block (7 cols) --}}
            <div class="lg:col-span-7 space-y-5">
                <div class="flex items-center gap-3 flex-wrap">
                    <span class="inline-flex items-center px-3 py-1 bg-violet-50 border border-violet-100 text-violet-700 rounded-full text-[11px] font-bold uppercase tracking-widest">
                        {{ $event->category->name }}
                    </span>
                    @if($event->reviews()->count() > 0)
                        <a href="{{ route('events.reviews', $event->id) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-neutral-500 hover:text-violet-650 transition">
                            <svg class="w-4 h-4 text-amber-400 fill-amber-400 stroke-[1.75]" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c.198-.625 1.077-.625 1.275 0l2.022 6.388c.089.281.353.475.648.475h6.718c.658 0 .93.84.398 1.235l-5.434 3.947a.3.3 0 00-.108.334l2.022 6.388c.198.625-.529 1.154-1.062.772l-5.434-3.947a.3.3 0 00-.334 0l-5.434 3.947c-.533.382-1.26-.147-1.062-.772l2.022-6.388a.3.3 0 00-.108-.334L1.75 11.597c-.532-.395-.26-1.235.398-1.235h6.718c.295 0 .559-.194.648-.475l2.022-6.388z"></path>
                            </svg>
                            <span class="text-neutral-800 font-bold">{{ number_format($event->average_rating, 1) }}</span>
                            <span class="text-neutral-400">({{ $event->reviews()->count() }} ulasan)</span>
                        </a>
                    @else
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-neutral-400">
                            <svg class="w-4 h-4 text-neutral-300 stroke-[1.75]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c.198-.625 1.077-.625 1.275 0l2.022 6.388c.089.281.353.475.648.475h6.718c.658 0 .93.84.398 1.235l-5.434 3.947a.3.3 0 00-.108.334l2.022 6.388c.198.625-.529 1.154-1.062.772l-5.434-3.947a.3.3 0 00-.334 0l-5.434 3.947c-.533.382-1.26-.147-1.062-.772l2.022-6.388a.3.3 0 00-.108-.334L1.75 11.597c-.532-.395-.26-1.235.398-1.235h6.718c.295 0 .559-.194.648-.475l2.022-6.388z"></path>
                            </svg>
                            Belum ada ulasan
                        </span>
                    @endif
                </div>

                <h1 class="text-[2.6rem] lg:text-[3.25rem] font-bold leading-[1.1] text-neutral-900" style="letter-spacing:-0.02em">
                    {{ $event->title }}
                </h1>

            </div>

    </div>
</section>

{{-- ─── MAIN CONTENT ────────────────────────────────────────────────────────── --}}
<main class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14">

        {{-- ── LEFT COLUMN: Poster + About + Policy (8 cols) ───────────────── --}}
        <div class="lg:col-span-8 space-y-10">

            {{-- Poster Image --}}
            <div class="w-full overflow-hidden rounded-3xl border border-neutral-200 bg-neutral-100">
                @php $hasPoster = $event->poster_path && Storage::disk('public')->exists($event->poster_path); @endphp
                @if($hasPoster)
                    <img src="{{ asset('storage/' . $event->poster_path) }}"
                         alt="{{ $event->title }}"
                         class="w-full object-cover max-h-[420px] object-center">
                @else
                    <img src="https://placehold.co/800x420?text=No+Poster"
                         alt="No Poster"
                         class="w-full object-cover max-h-[420px] object-center">
                @endif
            </div>

            {{-- About Section --}}
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-1 h-5 bg-violet-500 rounded-full"></div>
                    <h2 class="text-base font-bold text-neutral-900" style="letter-spacing:-0.01em">Tentang Event</h2>
                </div>
                <p class="text-sm text-neutral-600 leading-[1.8] whitespace-pre-line pl-4">
                    {{ $event->description }}
                </p>
            </div>

            {{-- Divider --}}
            <div class="border-t border-neutral-100"></div>

            {{-- Policy Section --}}
            <div class="space-y-5">
                <div class="flex items-center gap-3">
                    <div class="w-1 h-5 bg-violet-500 rounded-full"></div>
                    <h2 class="text-base font-bold text-neutral-900" style="letter-spacing:-0.01em">Ketentuan & Kebijakan</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    {{-- Policy 1 --}}
                    <div class="group flex flex-col gap-3 p-5 rounded-2xl border border-neutral-200 bg-white hover:border-neutral-300 transition-colors duration-150">
                        <div class="w-8 h-8 rounded-xl bg-neutral-100 border border-neutral-200 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-neutral-800 mb-1 leading-tight">E-Ticket Instan</p>
                            <p class="text-[11px] text-neutral-500 leading-relaxed font-medium">
                                Dikirim otomatis ke email setelah transaksi terverifikasi.
                            </p>
                        </div>
                    </div>

                    {{-- Policy 2 --}}
                    <div class="group flex flex-col gap-3 p-5 rounded-2xl border border-neutral-200 bg-white hover:border-neutral-300 transition-colors duration-150">
                        <div class="w-8 h-8 rounded-xl bg-neutral-100 border border-neutral-200 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m0 14v1m8-9h-1m-14 0H3m2.222-5.636l.707.707m12.122 12.122l.707.707M5.05 18.95l.707-.707m12.122-12.122l.707-.707M12 9a3 3 0 110 6 3 3 0 010-6z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-neutral-800 mb-1 leading-tight">Scan & Check-in</p>
                            <p class="text-[11px] text-neutral-500 leading-relaxed font-medium">
                                Tunjukkan barcode e-ticket saat tiba di lokasi acara.
                            </p>
                        </div>
                    </div>

                    {{-- Policy 3 --}}
                    <div class="group flex flex-col gap-3 p-5 rounded-2xl border border-neutral-200 bg-white hover:border-neutral-300 transition-colors duration-150">
                        <div class="w-8 h-8 rounded-xl bg-neutral-100 border border-neutral-200 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-neutral-800 mb-1 leading-tight">Non-Refundable</p>
                            <p class="text-[11px] text-neutral-500 leading-relaxed font-medium">
                                Tiket yang sudah dibeli bersifat final dan tidak dapat dikembalikan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Divider --}}
            <div class="border-t border-neutral-100 my-10"></div>

            {{-- Review and Rating Section --}}
            <div class="space-y-6">
                <div class="flex items-center gap-3">
                    <div class="w-1 h-5 bg-violet-500 rounded-full"></div>
                    <h2 class="text-base font-bold text-neutral-900" style="letter-spacing:-0.01em">Ulasan & Rating Event</h2>
                </div>

                @php
                    $eventDate = \Carbon\Carbon::parse($event->date);
                    $reviewAllowedAfter = $eventDate->copy()->addDay();
                    $isAllowedToReview = now()->greaterThanOrEqualTo($reviewAllowedAfter);
                    
                    $hasPurchased = false;
                    $hasReviewed = false;
                    $userReview = null;

                    if (auth()->check()) {
                        $hasPurchased = \App\Models\Transaction::where('customer_email', auth()->user()->email)
                            ->where('event_id', $event->id)
                            ->where('status', 'Success')
                            ->exists();

                        $userReview = \App\Models\Review::where('user_id', auth()->id())
                            ->where('event_id', $event->id)
                            ->first();
                        
                        $hasReviewed = !is_null($userReview);
                    }
                @endphp

                @if(!auth()->check())
                    <div class="bg-violet-50/50 border border-violet-100 rounded-2xl p-6 text-center">
                        <p class="text-sm text-neutral-600 mb-4">Hanya pembeli tiket yang sah yang dapat memberikan ulasan dan rating bintang.</p>
                        <a href="{{ route('admin.login') }}" class="inline-flex items-center justify-center h-10 px-6 rounded-lg bg-violet-500 hover:bg-violet-600 text-white font-semibold text-sm transition-colors duration-150 shadow-sm">
                            Login untuk Memberikan Ulasan
                        </a>
                    </div>
                @else
                    @if(!$isAllowedToReview)
                        <div class="bg-neutral-100 border border-neutral-200 rounded-2xl p-5 text-neutral-600 text-sm">
                            <span class="font-semibold text-neutral-800">Ulasan belum dibuka:</span> Ulasan baru dapat diberikan 1 hari setelah acara selesai (mulai {{ $reviewAllowedAfter->translatedFormat('d M Y, H:i') }} WIB).
                        </div>
                    @elseif($hasReviewed)
                        <div class="bg-emerald-50/65 border border-emerald-100 rounded-2xl p-6 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-emerald-700 flex items-center gap-1">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Anda Sudah Memberikan Ulasan
                                </span>
                                <div class="flex gap-0.5 text-amber-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $userReview->rating ? 'text-amber-400 fill-amber-400' : 'text-neutral-200 fill-none' }}" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M11.48 3.499c.198-.625 1.077-.625 1.275 0l2.022 6.388c.089.281.353.475.648.475h6.718c.658 0 .93.84.398 1.235l-5.434 3.947a.3.3 0 00-.108.334l2.022 6.388c.198.625-.529 1.154-1.062.772l-5.434-3.947a.3.3 0 00-.334 0l-5.434 3.947c-.533.382-1.26-.147-1.062-.772l2.022-6.388a.3.3 0 00-.108-.334L1.75 11.597c-.532-.395-.26-1.235.398-1.235h6.718c.295 0 .559-.194.648-.475l2.022-6.388z"></path>
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                            @if($userReview->comment)
                                <p class="text-sm text-neutral-800 leading-relaxed font-normal bg-white p-3.5 rounded-xl border border-neutral-100 italic">
                                    "{{ $userReview->comment }}"
                                </p>
                            @endif
                        </div>
                    @elseif(!$hasPurchased)
                        <div class="bg-rose-50/60 border border-rose-100 rounded-2xl p-5 text-rose-800 text-sm">
                            Hanya pembeli tiket terdaftar dengan status transaksi 'Success' yang dapat memberikan ulasan untuk event ini.
                        </div>
                    @else
                        <!-- Form Review Langsung di Detail Event -->
                        <div class="bg-white border border-neutral-200 rounded-2xl p-6 shadow-sm">
                            <form id="directReviewForm" class="space-y-5" onsubmit="submitDirectReview(event)">
                                @csrf
                                <input type="hidden" name="event_id" value="{{ $event->id }}" id="directEventId">
                                
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-neutral-800">Berikan Penilaian Bintang</label>
                                    <div class="flex items-center gap-1.5" id="directStarContainer">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button type="button" onclick="selectDirectRating({{ $i }})" onmouseover="highlightDirectStars({{ $i }})" onmouseout="resetDirectStars()" class="text-neutral-300 hover:scale-110 transition-transform duration-100" data-direct-star="{{ $i }}">
                                                <svg class="w-8 h-8 stroke-[1.75]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c.198-.625 1.077-.625 1.275 0l2.022 6.388c.089.281.353.475.648.475h6.718c.658 0 .93.84.398 1.235l-5.434 3.947a.3.3 0 00-.108.334l2.022 6.388c.198.625-.529 1.154-1.062.772l-5.434-3.947a.3.3 0 00-.334 0l-5.434 3.947c-.533.382-1.26-.147-1.062-.772l2.022-6.388a.3.3 0 00-.108-.334L1.75 11.597c-.532-.395-.26-1.235.398-1.235h6.718c.295 0 .559-.194.648-.475l2.022-6.388z"></path>
                                                </svg>
                                            </button>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="rating" id="directRatingInput" required>
                                    <p class="text-xs text-rose-500 hidden" id="directRatingError">Silakan pilih rating bintang terlebih dahulu.</p>
                                </div>

                                <div class="space-y-1.5">
                                    <div class="flex justify-between items-center">
                                        <label for="directComment" class="block text-sm font-bold text-neutral-800">Ulasan Tertulis</label>
                                        <span class="text-[11px] text-neutral-400 font-medium" id="directCharCounter">0 / 500</span>
                                    </div>
                                    <textarea name="comment" id="directComment" rows="3" maxlength="500" oninput="updateDirectCharCount(this)" placeholder="Ceritakan keseruan acara atau kritik dan saran membangun..." class="w-full min-h-[96px] p-3 text-sm text-neutral-800 border border-neutral-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-violet-50 focus:border-violet-400 hover:border-neutral-400 transition-colors duration-150 resize-none"></textarea>
                                </div>

                                <div class="flex items-center justify-end">
                                    <button type="submit" class="h-10 px-6 bg-violet-500 hover:bg-violet-600 text-white rounded-lg font-bold text-xs transition duration-150 flex items-center justify-center gap-2 shadow-sm">
                                        <span id="directSubmitBtnText">Kirim Ulasan</span>
                                        <svg id="directSubmitLoadingSpinner" class="animate-spin -mr-1 ml-2 h-4 w-4 text-white hidden" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                @endif

                <!-- Feed Ulasan Singkat (Preview) -->
                @if($event->reviews()->count() > 0)
                    <div class="border-t border-neutral-100 pt-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 leading-none">Ulasan Terbaru</h3>
                            <a href="{{ route('events.reviews', $event->id) }}" class="text-xs font-bold text-violet-600 hover:text-violet-850 transition flex items-center gap-0.5">
                                Lihat Semua ({{ $event->reviews()->count() }}) &rarr;
                            </a>
                        </div>
                        <div class="space-y-3.5">
                            @foreach($event->reviews()->latest()->take(3)->get() as $rev)
                                @php
                                    $reviewerName = $rev->user->name ?? 'Anonim';
                                    $parts = explode(' ', $reviewerName);
                                    if (count($parts) > 1) {
                                        $obfuscatedName = $parts[0] . ' ' . substr($parts[1], 0, 1) . '***';
                                    } elseif (strlen($reviewerName) > 3) {
                                        $obfuscatedName = substr($reviewerName, 0, 3) . '***';
                                    } else {
                                        $obfuscatedName = $reviewerName . '***';
                                    }
                                @endphp
                                <div class="bg-neutral-50/50 border border-neutral-100 rounded-xl p-4 space-y-2">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-bold text-neutral-850">{{ $obfuscatedName }}</span>
                                        <div class="flex gap-0.5 text-amber-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-3.5 h-3.5 {{ $i <= $rev->rating ? 'text-amber-400 fill-amber-400' : 'text-neutral-200 fill-none' }}" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="M11.48 3.499c.198-.625 1.077-.625 1.275 0l2.022 6.388c.089.281.353.475.648.475h6.718c.658 0 .93.84.398 1.235l-5.434 3.947a.3.3 0 00-.108.334l2.022 6.388c.198.625-.529 1.154-1.062.772l-5.434-3.947a.3.3 0 00-.334 0l-5.434 3.947c-.533.382-1.26-.147-1.062-.772l2.022-6.388a.3.3 0 00-.108-.334L1.75 11.597c-.532-.395-.26-1.235.398-1.235h6.718c.295 0 .559-.194.648-.475l2.022-6.388z"></path>
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-xs text-neutral-700 leading-relaxed font-normal">
                                        {{ $rev->comment ?? 'Pengguna hanya memberikan penilaian bintang.' }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- JavaScript untuk Interaktivitas Form Review Langsung -->
            <script>
                let directRating = 0;

                function highlightDirectStars(rating) {
                    for (let i = 1; i <= 5; i++) {
                        const star = document.querySelector(`[data-direct-star="${i}"]`);
                        if (star) {
                            const svg = star.querySelector('svg');
                            if (i <= rating) {
                                svg.setAttribute('fill', '#fbbf24');
                                svg.setAttribute('stroke', '#fbbf24');
                                star.classList.remove('text-neutral-300');
                                star.classList.add('text-amber-400');
                            } else {
                                svg.setAttribute('fill', 'none');
                                svg.setAttribute('stroke', 'currentColor');
                                star.classList.remove('text-amber-400');
                                star.classList.add('text-neutral-300');
                            }
                        }
                    }
                }

                function resetDirectStars() {
                    highlightDirectStars(directRating);
                }

                function selectDirectRating(rating) {
                    directRating = rating;
                    document.getElementById('directRatingInput').value = rating;
                    document.getElementById('directRatingError').classList.add('hidden');
                    highlightDirectStars(rating);
                }

                function updateDirectCharCount(textarea) {
                    const count = textarea.value.length;
                    document.getElementById('directCharCounter').textContent = `${count} / 500`;
                }

                function submitDirectReview(event) {
                    event.preventDefault();
                    const eventId = document.getElementById('directEventId').value;
                    const rating = document.getElementById('directRatingInput').value;
                    const comment = document.getElementById('directComment').value;

                    if (!rating) {
                        document.getElementById('directRatingError').classList.remove('hidden');
                        return;
                    }

                    // Show loading state
                    const submitBtnText = document.getElementById('directSubmitBtnText');
                    const spinner = document.getElementById('directSubmitLoadingSpinner');
                    submitBtnText.textContent = 'Mengirim...';
                    spinner.classList.remove('hidden');

                    fetch(`/reviews/${eventId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({
                            rating: rating,
                            comment: comment
                        })
                    })
                    .then(response => response.json().then(data => ({ status: response.status, body: data })))
                    .then(res => {
                        if (res.status === 200) {
                            alert(res.body.message);
                            window.location.reload();
                        } else {
                            alert(res.body.message || 'Terjadi kesalahan.');
                            submitBtnText.textContent = 'Kirim Ulasan';
                            spinner.classList.add('hidden');
                        }
                    })
                    .catch(err => {
                        alert('Terjadi kesalahan koneksi.');
                        submitBtnText.textContent = 'Kirim Ulasan';
                        spinner.classList.add('hidden');
                    });
                }
            </script>
        </div>

        {{-- ── RIGHT COLUMN: Sticky Ticket Widget (4 cols) ─────────────────── --}}
        <div class="lg:col-span-4">
            <div class="sticky top-[100px] space-y-4">

                {{-- Main Ticket Card --}}
                <div class="rounded-3xl border border-neutral-200 bg-white overflow-hidden shadow-[0_4px_24px_0_rgba(15,23,42,0.06)]">

                    {{-- Price header --}}
                    <div class="px-7 pt-7 pb-6 border-b border-neutral-100">
                        <p class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 leading-none mb-3">Harga Tiket</p>
                        @if($event->price == 0)
                            <div class="flex items-baseline gap-1.5">
                                <span class="text-3xl font-bold text-emerald-500" style="letter-spacing:-0.02em">GRATIS</span>
                            </div>
                        @else
                            <div class="flex items-baseline gap-1.5">
                                <span class="text-3xl font-bold text-neutral-900" style="letter-spacing:-0.02em">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                                <span class="text-xs text-neutral-400 font-semibold">/ orang</span>
                            </div>
                        @endif
                    </div>

                    {{-- Stock row --}}
                    <div class="px-7 py-5 border-b border-neutral-100 flex items-center">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 {{ $event->stock > 10 ? 'text-violet-400' : ($event->stock > 0 ? 'text-amber-500' : 'text-rose-400') }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                            <div>
                                <p class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 leading-none">Ketersediaan</p>
                                @if($event->stock > 0)
                                    <p class="text-[12px] font-bold text-neutral-800 mt-0.5 leading-none">{{ $event->stock }} tiket tersisa</p>
                                @else
                                    <p class="text-[12px] font-bold text-rose-500 mt-0.5 leading-none">Tiket habis</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- CTA Block --}}
                    <div class="px-7 py-6 space-y-3">
                        @php $isEventEnded = \Carbon\Carbon::parse($event->date)->isPast(); @endphp
                        @if($isEventEnded)
                            <div class="text-center space-y-3">
                                <div class="w-full px-5 py-3.5 bg-neutral-100 rounded-xl border border-neutral-200 text-center">
                                    <p class="text-xs font-bold text-neutral-500">Event Telah Selesai</p>
                                </div>
                                @if($event->reviews()->count() > 0)
                                    <a href="{{ route('events.reviews', $event->id) }}"
                                       class="flex items-center justify-center w-full px-5 py-3 bg-white hover:bg-violet-50 text-violet-600 font-semibold text-sm rounded-xl transition border border-violet-200">
                                        Baca Ulasan Peserta
                                    </a>
                                @endif
                            </div>
                        @elseif($event->stock > 0)
                            <a href="{{ route('checkout.create', $event->id) }}"
                               class="flex items-center justify-center w-full px-5 py-3.5 bg-violet-600 hover:bg-violet-500 active:bg-violet-700 text-white font-semibold text-sm rounded-xl transition-all duration-150 shadow-sm hover:shadow-md hover:shadow-violet-500/20"
                               style="letter-spacing:-0.005em">
                                Pesan Tiket Sekarang
                            </a>
                        @else
                            <button disabled
                                    class="flex items-center justify-center w-full px-5 py-3.5 bg-neutral-100 text-neutral-400 font-semibold text-sm rounded-xl cursor-not-allowed border border-neutral-200">
                                Tiket Habis Terjual
                            </button>
                        @endif

                        @if(!$isEventEnded)
                            <p class="text-center text-[10px] text-neutral-400 font-medium">
                                Pembayaran aman melalui <span class="font-bold text-neutral-500">Midtrans</span>
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Organizer Card --}}
                <div class="rounded-2xl border border-neutral-200 bg-white px-5 py-4 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="w-10 h-10 rounded-xl bg-violet-50 border border-violet-100 flex items-center justify-center font-bold text-violet-700 text-xs flex-shrink-0">
                            AB
                        </div>
                        <div class="min-w-0">
                            <p class="text-[9px] text-neutral-400 font-bold uppercase tracking-widest leading-none mb-1">Penyelenggara</p>
                            <p class="text-xs font-bold text-neutral-800 truncate leading-tight">ABP Productions</p>
                            <p class="text-[10px] font-semibold text-emerald-600 mt-0.5 leading-none">✓ Terverifikasi</p>
                        </div>
                    </div>
                    @if($event->reviews()->count() > 0)
                        <div class="text-right flex-shrink-0">
                            <a href="{{ route('events.reviews', $event->id) }}" class="flex flex-col items-end">
                                <span class="text-xs font-extrabold text-neutral-800 flex items-center gap-0.5">
                                    ★ {{ number_format($event->average_rating, 1) }}
                                </span>
                                <span class="text-[9px] text-neutral-400 hover:text-violet-650 hover:underline transition">Lihat Ulasan</span>
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Datetime + Location Cards --}}
                <div class="rounded-2xl border border-neutral-200 bg-white px-5 py-4 space-y-3.5">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-neutral-100 border border-neutral-200 flex items-center justify-center text-neutral-400 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 leading-none">Tanggal & Waktu</p>
                            <p class="text-xs font-bold text-neutral-800 mt-1 leading-tight">{{ \Carbon\Carbon::parse($event->date)->format('l, d M Y') }}</p>
                            <p class="text-[10px] font-semibold text-neutral-500 mt-0.5">{{ \Carbon\Carbon::parse($event->date)->format('H:i') }} WIB</p>
                        </div>
                    </div>

                    <div class="border-t border-neutral-100"></div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-neutral-100 border border-neutral-200 flex items-center justify-center text-neutral-400 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 leading-none">Lokasi</p>
                            <p class="text-xs font-bold text-neutral-800 mt-1 leading-tight">{{ $event->location }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</main>

{{-- ─── MOBILE BOTTOM BAR ───────────────────────────────────────────────────── --}}
<div class="fixed bottom-0 inset-x-0 z-50 lg:hidden bg-white border-t border-neutral-200 px-5 py-3.5 flex items-center justify-between gap-4 shadow-[0_-4px_20px_rgba(15,23,42,0.06)]">
    <div>
        <p class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 leading-none">Harga / orang</p>
        @if($isEventEnded)
            <p class="text-base font-bold text-neutral-400 mt-1">Event Selesai</p>
        @elseif($event->price == 0)
            <p class="text-base font-bold text-emerald-600 mt-1">GRATIS</p>
        @else
            <p class="text-base font-bold text-neutral-900 mt-1">Rp {{ number_format($event->price, 0, ',', '.') }}</p>
        @endif
    </div>

    @if($isEventEnded)
        <a href="{{ route('events.reviews', $event->id) }}"
           class="flex-shrink-0 px-6 py-3 bg-neutral-100 text-neutral-500 text-sm font-semibold rounded-xl transition border border-neutral-200 hover:bg-violet-50 hover:text-violet-600 hover:border-violet-200">
            Lihat Ulasan
        </a>
    @elseif($event->stock > 0)
        <a href="{{ route('checkout.create', $event->id) }}"
           class="flex-shrink-0 px-6 py-3 bg-violet-600 hover:bg-violet-500 text-white text-sm font-semibold rounded-xl transition-all duration-150 active:scale-95">
            Pesan Tiket
        </a>
    @else
        <button disabled class="flex-shrink-0 px-6 py-3 bg-neutral-100 text-neutral-400 text-sm font-semibold rounded-xl cursor-not-allowed border border-neutral-200">
            Tiket Habis
        </button>
    @endif
</div>

@endsection