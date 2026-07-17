@extends('layouts.admin')

@section('title', 'Manajemen Event')

@section('content')
<div>
    <!-- Page Header -->
    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px;gap:16px;">
        <div>
            <h1 style="font-size:24px;font-weight:700;color:#0f172a;letter-spacing:-0.02em;margin-bottom:4px;">Manajemen Event</h1>
            <p style="font-size:14px;color:#475569;">Kelola seluruh data acara dan kegiatan yang tersedia.</p>
        </div>
        <a href="{{ route('admin.events.create') }}"
           style="display:inline-flex;align-items:center;gap:6px;height:40px;padding:0 20px;
                  background:#491e85;color:#fff;border-radius:8px;font-size:14px;font-weight:600;
                  text-decoration:none;white-space:nowrap;transition:background 150ms;flex-shrink:0;"
           onmouseover="this.style.background='#5e26ac'" onmouseout="this.style.background='#491e85'">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Event
        </a>
    </div>

    @if(session('success'))
    <div style="background:#f0fdf4;border:1px solid #86efac;border-radius:12px;padding:12px 16px;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
        <svg width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span style="font-size:14px;color:#15803d;font-weight:600;">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div style="background:#fff1f2;border:1px solid #fca5a5;border-radius:12px;padding:12px 16px;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
        <svg width="16" height="16" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span style="font-size:14px;color:#b91c1c;font-weight:600;">{{ session('error') }}</span>
    </div>
    @endif

    {{-- Search & Filter --}}
    <form action="{{ route('admin.events.index') }}" method="GET"
          style="display:flex;gap:10px;align-items:center;margin-bottom:20px;flex-wrap:wrap;">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari judul event..."
               style="flex:1;min-width:200px;height:44px;padding:0 16px;border:1px solid #e2e8f0;border-radius:12px;
                      font-size:14px;color:#1e293b;background:#fff;outline:none;font-family:'Manrope',sans-serif;"
               onfocus="this.style.borderColor='#9d5ef5';this.style.boxShadow='0 0 0 4px #f3ebfe';"
               onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none';">
        <select name="filter"
                style="height:44px;padding:0 12px;border:1px solid #e2e8f0;border-radius:12px;
                       font-size:14px;color:#1e293b;background:#fff;outline:none;font-family:'Manrope',sans-serif;">
            <option value="">— Urutkan —</option>
            <option value="title_asc"  {{ ($filter??'')=='title_asc'  ?'selected':'' }}>Judul (A → Z)</option>
            <option value="title_desc" {{ ($filter??'')=='title_desc' ?'selected':'' }}>Judul (Z → A)</option>
            <option value="oldest"     {{ ($filter??'')=='oldest'     ?'selected':'' }}>Terlama</option>
            <option value="newest"     {{ ($filter??'')=='newest'     ?'selected':'' }}>Terbaru</option>
        </select>
        @if(auth()->user()->isSuperadmin())
        <select name="status_filter"
                style="height:44px;padding:0 12px;border:1px solid #e2e8f0;border-radius:12px;
                       font-size:14px;color:#1e293b;background:#fff;outline:none;font-family:'Manrope',sans-serif;">
            <option value="all" {{ ($statusFilter??'')=='all'?'selected':'' }}>— Semua Status —</option>
            <option value="pending" {{ ($statusFilter??'')=='pending'?'selected':'' }}>Pending</option>
            <option value="approved" {{ ($statusFilter??'')=='approved'?'selected':'' }}>Approved</option>
            <option value="rejected" {{ ($statusFilter??'')=='rejected'?'selected':'' }}>Rejected</option>
        </select>
        @endif
        <select name="category_filter"
                style="height:44px;padding:0 12px;border:1px solid #e2e8f0;border-radius:12px;
                       font-size:14px;color:#1e293b;background:#fff;outline:none;font-family:'Manrope',sans-serif;">
            <option value="all" {{ ($categoryFilter??'')=='all'?'selected':'' }}>— Semua Kategori —</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ ($categoryFilter??'')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <button type="submit"
                style="height:44px;padding:0 20px;background:#7831dc;color:#fff;border:none;border-radius:12px;
                       font-size:14px;font-weight:600;cursor:pointer;font-family:'Manrope',sans-serif;transition:all 150ms;"
                onmouseover="this.style.background='#5e26ac'" onmouseout="this.style.background='#7831dc'">Cari</button>
        @if(($search??'')||($filter??'')||(($statusFilter??'') && ($statusFilter??'')!='all')||(($categoryFilter??'') && ($categoryFilter??'')!='all'))
        <a href="{{ route('admin.events.index') }}"
           style="height:44px;padding:0 20px;display:inline-flex;align-items:center;background:#fff;color:#475569;
                  border:1px solid #e2e8f0;border-radius:12px;font-size:14px;font-weight:600;text-decoration:none;"
           onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">Reset</a>
        @endif
    </form>

    {{-- Table --}}
    <div style="background:#fff;border:1px solid #f1f5f9;border-radius:16px;overflow:hidden;box-shadow:0 1px 3px 0 rgba(15,23,42,.03);">
        <div class="overflow-x-auto">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:1px solid #f1f5f9;">
                        <th style="padding:12px 20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;text-align:left;width:50px;">No</th>
                        <th style="padding:12px 20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;text-align:left;">Poster</th>
                        <th style="padding:12px 20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;text-align:left;">Judul Event</th>
                        @if(auth()->user()->isSuperadmin())
                        <th style="padding:12px 20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;text-align:left;">Penyelenggara</th>
                        @endif
                        <th style="padding:12px 20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;text-align:left;">Kategori</th>
                        <th style="padding:12px 20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;text-align:left;">Sisa Stok</th>
                        <th style="padding:12px 20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;text-align:left;">Status</th>
                        <th style="padding:12px 20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;text-align:left;">Tanggal</th>
                        <th style="padding:12px 20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;text-align:left;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $index => $event)
                    <tr style="border-bottom:1px solid #f1f5f9;transition:background 150ms;"
                        onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:14px 20px;font-size:14px;color:#94a3b8;">{{ $events->firstItem() + $index }}</td>
                        <td style="padding:14px 20px;">
                            <img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path))
                                ? asset('storage/' . $event->poster_path)
                                : 'https://placehold.co/64x80/f1f5f9/94a3b8?text=No+Img' }}"
                                 style="width:48px;height:60px;border-radius:10px;object-fit:cover;border:1px solid #f1f5f9;">
                        </td>
                        <td style="padding:14px 20px;font-size:14px;font-weight:600;color:#1e293b;max-width:200px;">
                            <span style="display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $event->title }}</span>
                        </td>
                        @if(auth()->user()->isSuperadmin())
                        <td style="padding:14px 20px;font-size:13.5px;color:#475569;">
                            {{ $event->organizer->name ?? '-' }}
                        </td>
                        @endif
                        <td style="padding:14px 20px;font-size:13.5px;color:#475569;">
                            {{ $event->category->name ?? '-' }}
                        </td>
                        <td style="padding:14px 20px;font-size:14px;color:#475569;">
                            {{ $event->stock }}
                        </td>
                        <td style="padding:14px 20px;">
                            @php $badge = $event->status_badge; @endphp
                            <span style="display:inline-flex;padding:3px 10px;background:{{ $badge['bg'] }};color:{{ $badge['text'] }};border:1px solid {{ $badge['border'] }};border-radius:6px;font-size:12px;font-weight:600;">
                                {{ $badge['label'] }}
                            </span>
                        </td>
                        <td style="padding:14px 20px;font-size:13px;color:#94a3b8;white-space:nowrap;">
                            {{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }}
                        </td>
                        <td style="padding:14px 20px;">
                            <div style="display:flex;gap:6px;align-items:center;">
                                <a href="{{ route('admin.events.edit', $event->id) }}"
                                   title="Edit"
                                   style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;
                                          background:#f1f5f9;border-radius:8px;text-decoration:none;transition:background 150ms;"
                                   onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                                    <svg width="14" height="14" fill="none" stroke="#475569" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus event ini secara permanen?');" style="margin:0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="Hapus"
                                            style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;
                                                   background:#fff1f2;border-radius:8px;border:none;cursor:pointer;transition:background 150ms;"
                                            onmouseover="this.style.background='#ffe4e6'" onmouseout="this.style.background='#fff1f2'">
                                        <svg width="14" height="14" fill="none" stroke="#e11d48" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding:56px 24px;text-align:center;color:#94a3b8;font-size:14px;">
                            Belum ada event. Klik "Tambah Event" untuk menambahkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($events->hasPages())
        <div style="padding:16px 20px;border-top:1px solid #f1f5f9;display:flex;align-items:center;flex-wrap:wrap;gap:12px;">
            <p style="font-size:13px;color:#94a3b8;flex:1;">
                Menampilkan <span style="color:#1e293b;font-weight:600;">{{ $events->firstItem() }}</span>
                – <span style="color:#1e293b;font-weight:600;">{{ $events->lastItem() }}</span>
                dari <span style="color:#1e293b;font-weight:600;">{{ $events->total() }}</span> data
            </p>
            <div style="display:flex;gap:4px;align-items:center;">
                @if($events->onFirstPage())
                    <span style="padding:6px 12px;font-size:13px;color:#cbd5e1;background:#f8fafc;border:1px solid #f1f5f9;border-radius:8px;cursor:not-allowed;">‹ Prev</span>
                @else
                    <a href="{{ $events->appends(['search'=>$search,'filter'=>$filter,'status_filter'=>$statusFilter,'category_filter'=>$categoryFilter])->previousPageUrl() }}"
                       style="padding:6px 12px;font-size:13px;color:#475569;background:#fff;border:1px solid #e2e8f0;border-radius:8px;text-decoration:none;transition:all 150ms;"
                       onmouseover="this.style.background='#f3ebfe';this.style.color='#8436f2';"
                       onmouseout="this.style.background='#fff';this.style.color='#475569';">‹ Prev</a>
                @endif
                @foreach($events->getUrlRange(1, $events->lastPage()) as $page => $url)
                    @if($page == $events->currentPage())
                        <span style="padding:6px 12px;font-size:13px;font-weight:700;color:#fff;background:#ad78f6;border:1px solid #ad78f6;border-radius:8px;">{{ $page }}</span>
                    @else
                        <a href="{{ $events->appends(['search'=>$search,'filter'=>$filter,'status_filter'=>$statusFilter,'category_filter'=>$categoryFilter])->url($page) }}"
                           style="padding:6px 12px;font-size:13px;color:#475569;background:#fff;border:1px solid #e2e8f0;border-radius:8px;text-decoration:none;"
                           onmouseover="this.style.background='#f3ebfe';this.style.color='#8436f2';"
                           onmouseout="this.style.background='#fff';this.style.color='#475569';">{{ $page }}</a>
                    @endif
                @endforeach
                @if($events->hasMorePages())
                    <a href="{{ $events->appends(['search'=>$search,'filter'=>$filter,'status_filter'=>$statusFilter,'category_filter'=>$categoryFilter])->nextPageUrl() }}"
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
</div>
@endsection