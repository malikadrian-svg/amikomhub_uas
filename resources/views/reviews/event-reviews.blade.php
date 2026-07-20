@extends('layouts.app')

@section('title', 'Ulasan Penonton - ' . $event->title)

@section('content')
    <div class="bg-neutral-50 text-neutral-800 min-h-[calc(100vh-72px)] py-12 px-6">
        <div class="max-w-4xl mx-auto">
            <!-- Breadcrumb Navigation -->
            <nav class="mb-6 flex items-center gap-2 text-xs font-semibold text-neutral-500">
                <a href="{{ route('home') }}" class="hover:text-violet-600 transition">Beranda</a>
                <span>&gt;</span>
                <a href="{{ route('events.show', $event->id) }}" class="hover:text-violet-600 transition">{{ $event->title }}</a>
                <span>&gt;</span>
                <span class="text-neutral-800">Ulasan & Rating</span>
            </nav>

            <!-- Event Header Card -->
            <div class="bg-white border border-neutral-100 rounded-2xl p-6 shadow-sm mb-8 flex flex-col md:flex-row gap-6 items-start md:items-center">
                @if($event->poster_path)
                    @php
                        $rvPath = $event->poster_path;
                        $rvUrl  = file_exists(public_path($rvPath)) ? asset($rvPath) : asset('storage/' . $rvPath);
                    @endphp
                    <img src="{{ $rvUrl }}" alt="{{ $event->title }}" class="w-24 h-24 md:w-32 md:h-32 object-cover rounded-xl border border-neutral-100 flex-shrink-0">
                @else
                    <div class="w-24 h-24 md:w-32 md:h-32 bg-violet-50 text-violet-500 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-12h9.75c1.05 0 2.025.208 2.925.586a10.457 10.457 0 013.314 2.228 10.457 10.457 0 012.228 3.314c.378.9.586 1.875.586 2.925v.75c0 1.05-.208 2.025-.586 2.925a10.457 10.457 0 01-2.228 3.314 10.457 10.457 0 01-3.314 2.228A10.463 10.463 0 0116.5 21h-9.75a1.5 1.5 0 01-1.5-1.5V7.5a1.5 1.5 0 011.5-1.5z"></path>
                        </svg>
                    </div>
                @endif
                <div class="flex-grow space-y-2">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-violet-50 text-violet-800">
                        {{ $event->category->name ?? 'Event' }}
                    </span>
                    <h1 class="text-2xl font-bold text-neutral-950 tracking-tight">{{ $event->title }}</h1>
                    <p class="text-neutral-600 text-sm flex items-center gap-1.5">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ \Carbon\Carbon::parse($event->date)->translatedFormat('d M Y, H:i') }} WIB
                    </p>
                    <p class="text-neutral-600 text-sm flex items-center gap-1.5">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $event->location }}
                    </p>
                </div>
            </div>

            <!-- Rating Breakdown Section -->
            <div class="bg-white border border-neutral-100 rounded-2xl p-6 md:p-8 shadow-sm mb-8">
                <h2 class="text-lg font-bold text-neutral-900 mb-6">Ulasan & Penilaian Peserta</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                    <!-- Left: Large average rate display -->
                    <div class="md:col-span-4 text-center md:border-r border-neutral-100 md:pr-8 py-2 space-y-2">
                        <div class="text-neutral-950 text-[40px] font-bold tracking-tight leading-none">
                            {{ number_format($event->average_rating, 1) }}
                        </div>
                        
                        <!-- Star display -->
                        <div class="flex justify-center gap-1">
                            @php $rounded = round($event->average_rating); @endphp
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $rounded ? 'text-amber-400 fill-amber-400' : 'text-neutral-200 fill-none' }} stroke-[1.75]" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c.198-.625 1.077-.625 1.275 0l2.022 6.388c.089.281.353.475.648.475h6.718c.658 0 .93.84.398 1.235l-5.434 3.947a.3.3 0 00-.108.334l2.022 6.388c.198.625-.529 1.154-1.062.772l-5.434-3.947a.3.3 0 00-.334 0l-5.434 3.947c-.533.382-1.26-.147-1.062-.772l2.022-6.388a.3.3 0 00-.108-.334L1.75 11.597c-.532-.395-.26-1.235.398-1.235h6.718c.295 0 .559-.194.648-.475l2.022-6.388z"></path>
                                </svg>
                            @endfor
                        </div>

                        <p class="text-xs text-neutral-500">Dari {{ $event->reviews()->count() }} ulasan pembeli</p>
                    </div>

                    <!-- Right: Percent distribution bars -->
                    <div class="md:col-span-8 space-y-3">
                        @php $distribution = $event->rating_distribution; @endphp
                        @foreach($distribution as $stars => $data)
                            <div class="flex items-center gap-3 text-xs text-neutral-600">
                                <span class="w-12 text-right font-medium flex items-center justify-end gap-1">
                                    {{ $stars }}
                                    <svg class="w-3.5 h-3.5 text-amber-400 fill-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </span>
                                
                                <!-- Progress Bar container -->
                                <div class="flex-grow h-1.5 bg-neutral-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-violet-500 rounded-full transition-all duration-300" style="width: {{ $data['percentage'] }}%"></div>
                                </div>
                                
                                <span class="w-10 text-right font-semibold text-neutral-800">{{ $data['percentage'] }}%</span>
                                <span class="w-14 text-neutral-400">({{ $data['count'] }})</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Review Feed Section -->
            <div class="space-y-6">
                <div class="flex items-center justify-between border-b border-neutral-100 pb-4">
                    <h3 class="text-base font-bold text-neutral-950">Daftar Komentar (Feed)</h3>
                    <span class="text-xs text-neutral-500">{{ $reviews->total() }} Komentar</span>
                </div>

                @if($reviews->isEmpty())
                    <div class="bg-white border border-neutral-100 rounded-2xl p-12 text-center shadow-sm">
                        <div class="w-12 h-12 bg-neutral-50 text-neutral-400 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"></path>
                            </svg>
                        </div>
                        <h4 class="text-sm font-bold text-neutral-800 mb-1">Belum Ada Ulasan Tertulis</h4>
                        <p class="text-neutral-500 text-xs max-w-xs mx-auto">Peserta event belum membagikan ulasan tertulis untuk acara ini.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($reviews as $review)
                            @php
                                // Helper to obfuscate reviewer name
                                $reviewerName = $review->user->name ?? 'Anonim';
                                $parts = explode(' ', $reviewerName);
                                if (count($parts) > 1) {
                                    $obfuscatedName = $parts[0] . ' ' . substr($parts[1], 0, 1) . '***';
                                } elseif (strlen($reviewerName) > 3) {
                                    $obfuscatedName = substr($reviewerName, 0, 3) . '***';
                                } else {
                                    $obfuscatedName = $reviewerName . '***';
                                }
                            @endphp
                            
                            <div class="bg-white border border-neutral-100 rounded-2xl p-5 shadow-sm space-y-3">
                                <!-- Top: User & Date & Stars -->
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-violet-50 text-violet-850 font-bold text-xs flex items-center justify-center">
                                            {{ strtoupper(substr($reviewerName, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-bold text-neutral-900 leading-tight">{{ $obfuscatedName }}</h4>
                                            <p class="text-[10px] text-neutral-400 mt-0.5">{{ $review->created_at->translatedFormat('d M Y') }}</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Stars Display -->
                                    <div class="flex gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400 fill-amber-400' : 'text-neutral-200 fill-none' }} stroke-[1.75]" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c.198-.625 1.077-.625 1.275 0l2.022 6.388c.089.281.353.475.648.475h6.718c.658 0 .93.84.398 1.235l-5.434 3.947a.3.3 0 00-.108.334l2.022 6.388c.198.625-.529 1.154-1.062.772l-5.434-3.947a.3.3 0 00-.334 0l-5.434 3.947c-.533.382-1.26-.147-1.062-.772l2.022-6.388a.3.3 0 00-.108-.334L1.75 11.597c-.532-.395-.26-1.235.398-1.235h6.718c.295 0 .559-.194.648-.475l2.022-6.388z"></path>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>

                                <!-- Bottom: Comment text -->
                                @if($review->comment)
                                    <p class="text-sm text-neutral-800 font-normal leading-relaxed pt-1">
                                        {{ $review->comment }}
                                    </p>
                                @else
                                    <p class="text-sm text-neutral-400 italic font-normal pt-1">
                                        Pengguna tidak meninggalkan komentar ulasan tertulis.
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $reviews->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
