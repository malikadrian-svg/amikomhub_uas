@extends('layouts.admin')

@section('title', 'Antrian Approval Event')

@section('content')
<div>
    {{-- Page Header --}}
    <div style="margin-bottom:28px;">
        <h1 style="font-size:24px;font-weight:700;color:#0f172a;letter-spacing:-0.02em;margin-bottom:4px;">
            Antrian Persetujuan Event
        </h1>
        <p style="font-size:14px;color:#475569;">
            Tinjau &amp; setujui event yang diajukan oleh para Organizer sebelum tampil ke publik.
        </p>
    </div>

    {{-- Status Stats --}}
    <div class="grid grid-cols-3 gap-4" style="margin-bottom:24px;">
        <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:14px;padding:18px 20px;text-align:center;">
            <p style="font-size:28px;font-weight:700;color:#92400e;letter-spacing:-0.02em;">{{ $stats['pending'] }}</p>
            <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#b45309;margin-top:4px;">Menunggu Review</p>
        </div>
        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:14px;padding:18px 20px;text-align:center;">
            <p style="font-size:28px;font-weight:700;color:#14532d;letter-spacing:-0.02em;">{{ $stats['approved'] }}</p>
            <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#15803d;margin-top:4px;">Disetujui</p>
        </div>
        <div style="background:#fff1f2;border:1px solid #fecdd3;border-radius:14px;padding:18px 20px;text-align:center;">
            <p style="font-size:28px;font-weight:700;color:#881337;letter-spacing:-0.02em;">{{ $stats['rejected'] }}</p>
            <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#be123c;margin-top:4px;">Ditolak</p>
        </div>
    </div>

    {{-- Search --}}
    <div style="margin-bottom:20px;">
        <form method="GET" action="{{ route('admin.approvals.index') }}">
            <div style="position:relative;max-width:360px;">
                <svg width="15" height="15" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24"
                     style="position:absolute;left:14px;top:50%;transform:translateY(-50%);">
                    <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                </svg>
                <input type="text" name="search" value="{{ $search ?? '' }}"
                       placeholder="Cari nama event..."
                       style="width:100%;height:40px;padding:0 14px 0 38px;border:1px solid #e2e8f0;border-radius:10px;
                              font-size:13px;font-family:'Manrope',sans-serif;color:#1e293b;background:#fff;outline:none;transition:border 150ms;"
                       onfocus="this.style.borderColor='#9d5ef5'" onblur="this.style.borderColor='#e2e8f0'">
            </div>
        </form>
    </div>

    {{-- Event List --}}
    @forelse($pendingEvents as $event)
        @php $isFirst = $loop->first; @endphp
        <div style="background:#fff;border:1px solid #f1f5f9;border-radius:16px;overflow:hidden;
                    box-shadow:0 1px 3px 0 rgba(15,23,42,.03);margin-bottom:12px;transition:box-shadow 150ms;"
             onmouseover="this.style.boxShadow='0 4px 12px rgba(15,23,42,.07)'" onmouseout="this.style.boxShadow='0 1px 3px 0 rgba(15,23,42,.03)'">
            <div style="display:flex;align-items:stretch;gap:0;">

                {{-- Poster --}}
                <div style="width:140px;flex-shrink:0;background:#f1f5f9;position:relative;overflow:hidden;">
                    @if($event->poster_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($event->poster_path))
                        <img src="{{ asset('storage/' . $event->poster_path) }}" alt=""
                             style="width:100%;height:100%;object-fit:cover;min-height:110px;">
                    @else
                        <div style="width:100%;height:100%;min-height:110px;display:flex;align-items:center;justify-content:center;">
                            <svg width="32" height="32" fill="none" stroke="#94a3b8" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Content --}}
                <div style="flex:1;padding:18px 22px;min-width:0;">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;">
                        <div style="min-width:0;flex:1;">
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                                <span style="font-size:10px;font-weight:700;background:#f3ebfe;color:#5e26ac;padding:2px 8px;border-radius:999px;border:1px solid #d9c1fb;">
                                    {{ $event->category->name ?? 'Tanpa Kategori' }}
                                </span>
                                <span style="font-size:10px;font-weight:700;background:#fffbeb;color:#b45309;padding:2px 8px;border-radius:999px;border:1px solid #fde68a;">
                                    ● Pending
                                </span>
                            </div>

                            <h3 style="font-size:16px;font-weight:700;color:#0f172a;letter-spacing:-0.01em;margin-bottom:6px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $event->title }}
                            </h3>

                            <div style="display:flex;flex-wrap:wrap;gap:16px;margin-bottom:10px;">
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <svg width="13" height="13" fill="none" stroke="#94a3b8" stroke-width="1.75" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                                    </svg>
                                    <span style="font-size:12px;color:#475569;font-weight:500;">{{ $event->organizer->name ?? 'N/A' }}</span>
                                </div>
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <svg width="13" height="13" fill="none" stroke="#94a3b8" stroke-width="1.75" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span style="font-size:12px;color:#475569;">{{ \Carbon\Carbon::parse($event->date)->translatedFormat('l, d M Y · H:i') }} WIB</span>
                                </div>
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <svg width="13" height="13" fill="none" stroke="#94a3b8" stroke-width="1.75" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    <span style="font-size:12px;color:#475569;">{{ $event->location }}</span>
                                </div>
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <svg width="13" height="13" fill="none" stroke="#94a3b8" stroke-width="1.75" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                    </svg>
                                    <span style="font-size:12px;color:#475569;">{{ $event->stock }} tiket · {{ $event->price == 0 ? 'GRATIS' : 'Rp ' . number_format($event->price, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            @if($event->description)
                            <p style="font-size:12px;color:#94a3b8;line-height:1.6;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                                {{ $event->description }}
                            </p>
                            @endif
                        </div>

                        {{-- Action Buttons --}}
                        <div style="display:flex;flex-direction:column;gap:8px;flex-shrink:0;">
                            <form method="POST" action="{{ route('admin.approvals.approve', $event->id) }}">
                                @csrf
                                <button type="submit"
                                    style="width:120px;height:38px;background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;
                                           border-radius:10px;font-size:12px;font-weight:700;cursor:pointer;transition:all 150ms;
                                           display:flex;align-items:center;justify-content:center;gap:6px;"
                                    onmouseover="this.style.background='#dcfce7';this.style.borderColor='#86efac';"
                                    onmouseout="this.style.background='#f0fdf4';this.style.borderColor='#bbf7d0';">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Approve
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.approvals.reject', $event->id) }}">
                                @csrf
                                <button type="submit"
                                    style="width:120px;height:38px;background:#fff1f2;color:#be123c;border:1px solid #fecdd3;
                                           border-radius:10px;font-size:12px;font-weight:700;cursor:pointer;transition:all 150ms;
                                           display:flex;align-items:center;justify-content:center;gap:6px;"
                                    onmouseover="this.style.background='#ffe4e6';this.style.borderColor='#fca5a5';"
                                    onmouseout="this.style.background='#fff1f2';this.style.borderColor='#fecdd3';">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Tolak
                                </button>
                            </form>
                            <a href="{{ route('admin.events.edit', $event->id) }}"
                               style="width:120px;height:38px;background:#f8fafc;color:#475569;border:1px solid #e2e8f0;
                                      border-radius:10px;font-size:12px;font-weight:600;cursor:pointer;transition:all 150ms;
                                      display:flex;align-items:center;justify-content:center;gap:6px;text-decoration:none;"
                               onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div style="background:#f8fafc;border:1px dashed #e2e8f0;border-radius:16px;padding:60px 24px;text-align:center;">
            <svg width="48" height="48" fill="none" stroke="#94a3b8" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p style="font-size:15px;font-weight:600;color:#475569;margin-bottom:6px;">Tidak ada event pending</p>
            <p style="font-size:13px;color:#94a3b8;">Semua pengajuan event sudah ditinjau. Mantap!</p>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if($pendingEvents->hasPages())
        <div style="margin-top:20px;">{{ $pendingEvents->links() }}</div>
    @endif
</div>
@endsection
