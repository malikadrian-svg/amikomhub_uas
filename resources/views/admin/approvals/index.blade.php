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
        <a href="{{ route('admin.approvals.index', ['status' => 'pending', 'search' => $search]) }}" 
           style="text-decoration:none;display:block;background:#fffbeb;border:1px solid {{ $status === 'pending' ? '#b45309' : '#fde68a' }};border-radius:14px;padding:18px 20px;text-align:center;box-shadow:{{ $status === 'pending' ? '0 4px 6px -1px rgba(146, 64, 14, 0.1), 0 2px 4px -1px rgba(146, 64, 14, 0.06)' : 'none' }};transition:transform 150ms;"
           onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
            <p style="font-size:28px;font-weight:700;color:#92400e;letter-spacing:-0.02em;margin:0;">{{ $stats['pending'] }}</p>
            <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#b45309;margin:4px 0 0;">Menunggu Review</p>
        </a>
        <a href="{{ route('admin.approvals.index', ['status' => 'approved', 'search' => $search]) }}" 
           style="text-decoration:none;display:block;background:#f0fdf4;border:1px solid {{ $status === 'approved' ? '#15803d' : '#bbf7d0' }};border-radius:14px;padding:18px 20px;text-align:center;box-shadow:{{ $status === 'approved' ? '0 4px 6px -1px rgba(21, 128, 61, 0.1), 0 2px 4px -1px rgba(21, 128, 61, 0.06)' : 'none' }};transition:transform 150ms;"
           onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
            <p style="font-size:28px;font-weight:700;color:#14532d;letter-spacing:-0.02em;margin:0;">{{ $stats['approved'] }}</p>
            <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#15803d;margin:4px 0 0;">Disetujui</p>
        </a>
        <a href="{{ route('admin.approvals.index', ['status' => 'rejected', 'search' => $search]) }}" 
           style="text-decoration:none;display:block;background:#fff1f2;border:1px solid {{ $status === 'rejected' ? '#be123c' : '#fecdd3' }};border-radius:14px;padding:18px 20px;text-align:center;box-shadow:{{ $status === 'rejected' ? '0 4px 6px -1px rgba(190, 18, 60, 0.1), 0 2px 4px -1px rgba(190, 18, 60, 0.06)' : 'none' }};transition:transform 150ms;"
           onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
            <p style="font-size:28px;font-weight:700;color:#881337;letter-spacing:-0.02em;margin:0;">{{ $stats['rejected'] }}</p>
            <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#be123c;margin:4px 0 0;">Ditolak</p>
        </a>
    </div>

    {{-- Search --}}
    <div style="margin-bottom:20px;">
        <form method="GET" action="{{ route('admin.approvals.index') }}">
            <input type="hidden" name="status" value="{{ $status }}">
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
    @forelse($events as $event)
        @php $isFirst = $loop->first; @endphp
        <div style="background:#fff;border:1px solid #f1f5f9;border-radius:16px;overflow:hidden;
                    box-shadow:0 1px 3px 0 rgba(15,23,42,.03);margin-bottom:12px;transition:box-shadow 150ms;"
             onmouseover="this.style.boxShadow='0 4px 12px rgba(15,23,42,.07)'" onmouseout="this.style.boxShadow='0 1px 3px 0 rgba(15,23,42,.03)'">
            <div style="display:flex;align-items:stretch;gap:0;">

                {{-- Poster --}}
                <div style="width:140px;flex-shrink:0;background:#f1f5f9;position:relative;overflow:hidden;">
                    @php
                        $apPath = $event->poster_path;
                        $apUrl = $apPath && (file_exists(public_path($apPath)) || file_exists(public_path('storage/' . $apPath)))
                            ? (file_exists(public_path($apPath)) ? asset($apPath) : asset('storage/' . $apPath))
                            : null;
                    @endphp
                    @if($apUrl)
                        <img src="{{ $apUrl }}" alt=""
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
                             <div style="margin-bottom:8px;">
                                <span style="font-size:12px;font-weight:600;color:#64748b;">
                                    {{ $event->category->name ?? 'Tanpa Kategori' }}
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
                            @if($event->status !== 'approved')
                            <form method="POST" action="{{ route('admin.approvals.approve', $event->id) }}"
                                  onsubmit="return confirm('Apakah Anda yakin ingin MENYETUJUI event &quot;{{ $event->title }}&quot;?')">
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
                            @endif
                            @if($event->status !== 'rejected')
                            <form method="POST" action="{{ route('admin.approvals.reject', $event->id) }}"
                                  onsubmit="return confirm('Apakah Anda yakin ingin MENOLAK event &quot;{{ $event->title }}&quot;?')">
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
                            @endif
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
            <p style="font-size:15px;font-weight:600;color:#475569;margin-bottom:6px;">Tidak ada event dengan status "{{ $status }}"</p>
            <p style="font-size:13px;color:#94a3b8;">Belum ada data event dalam status ini.</p>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if($events->hasPages())
        <div style="padding:16px 20px;border-top:1px solid #f1f5f9;display:flex;align-items:center;flex-wrap:wrap;gap:12px;margin-top:20px;">
            <p style="font-size:13px;color:#94a3b8;flex:1;">
                Menampilkan <span style="color:#1e293b;font-weight:600;">{{ $events->firstItem() }}</span>
                – <span style="color:#1e293b;font-weight:600;">{{ $events->lastItem() }}</span>
                dari <span style="color:#1e293b;font-weight:600;">{{ $events->total() }}</span> data
            </p>
            <div style="display:flex;gap:4px;align-items:center;">
                @if($events->onFirstPage())
                    <span style="padding:6px 12px;font-size:13px;color:#cbd5e1;background:#f8fafc;border:1px solid #f1f5f9;border-radius:8px;cursor:not-allowed;">‹ Prev</span>
                @else
                    <a href="{{ $events->appends(['search'=>$search,'status'=>$status])->previousPageUrl() }}"
                       style="padding:6px 12px;font-size:13px;color:#475569;background:#fff;border:1px solid #e2e8f0;border-radius:8px;text-decoration:none;transition:all 150ms;"
                       onmouseover="this.style.background='#f3ebfe';this.style.color='#8436f2';"
                       onmouseout="this.style.background='#fff';this.style.color='#475569';">‹ Prev</a>
                @endif
                @foreach($events->getUrlRange(1, $events->lastPage()) as $page => $url)
                    @if($page == $events->currentPage())
                        <span style="padding:6px 12px;font-size:13px;font-weight:700;color:#fff;background:#ad78f6;border:1px solid #ad78f6;border-radius:8px;">{{ $page }}</span>
                    @else
                        <a href="{{ $events->appends(['search'=>$search,'status'=>$status])->url($page) }}"
                           style="padding:6px 12px;font-size:13px;color:#475569;background:#fff;border:1px solid #e2e8f0;border-radius:8px;text-decoration:none;"
                           onmouseover="this.style.background='#f3ebfe';this.style.color='#8436f2';"
                           onmouseout="this.style.background='#fff';this.style.color='#475569';">{{ $page }}</a>
                    @endif
                @endforeach
                @if($events->hasMorePages())
                    <a href="{{ $events->appends(['search'=>$search,'status'=>$status])->nextPageUrl() }}"
                       style="padding:6px 12px;font-size:13px;color:#475569;background:#fff;border:1px solid #e2e8f0;border-radius:8px;text-decoration:none;"
                       onmouseover="this.style.background='#f3ebfe';this.style.color='#8436f2';"
                       onmouseout="this.style.background='#fff';this.style.color='#475569';">Next ›</a>
                @else
                    <span style="padding:6px 12px;font-size:13px;color:#cbd5e1;background:#f8fafc;border:1px solid #f1f5f9;border-radius:8px;cursor:not-allowed;">Next ›</span>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
