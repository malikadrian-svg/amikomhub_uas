@extends('layouts.admin')

@section('title', 'Manajemen Partner')

@section('content')
<div>
    <!-- Page Header -->
    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px;gap:16px;">
        <div>
            <h1 style="font-size:24px;font-weight:700;color:#0f172a;letter-spacing:-0.02em;margin-bottom:4px;">Manajemen Partner</h1>
            <p style="font-size:14px;color:#475569;">Kelola data mitra dan sponsor platform.</p>
        </div>
        <a href="{{ route('admin.partners.create') }}"
           style="display:inline-flex;align-items:center;gap:6px;height:40px;padding:0 20px;
                  background:#491e85;color:#fff;border-radius:8px;font-size:14px;font-weight:600;
                  text-decoration:none;white-space:nowrap;transition:background 150ms ease-out;flex-shrink:0;"
           onmouseover="this.style.background='#5e26ac'" onmouseout="this.style.background='#491e85'">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Partner
        </a>
    </div>

    {{-- Success Alert --}}
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
    <form action="{{ route('admin.partners.index') }}" method="GET"
          style="display:flex;gap:10px;align-items:center;margin-bottom:20px;flex-wrap:wrap;">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama partner..."
               style="flex:1;min-width:200px;height:44px;padding:0 16px;border:1px solid #e2e8f0;border-radius:12px;
                      font-size:14px;color:#1e293b;background:#fff;outline:none;font-family:'Manrope',sans-serif;
                      transition:border-color 150ms,box-shadow 150ms;"
               onfocus="this.style.borderColor='#9d5ef5';this.style.boxShadow='0 0 0 4px #f3ebfe';"
               onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none';">
        <select name="filter"
                style="height:44px;padding:0 12px;border:1px solid #e2e8f0;border-radius:12px;
                       font-size:14px;color:#1e293b;background:#fff;outline:none;font-family:'Manrope',sans-serif;">
            <option value="">— Urutkan —</option>
            <option value="name_asc"  {{ ($filter??'')=='name_asc'  ? 'selected':'' }}>Nama (A → Z)</option>
            <option value="name_desc" {{ ($filter??'')=='name_desc' ? 'selected':'' }}>Nama (Z → A)</option>
            <option value="oldest"    {{ ($filter??'')=='oldest'    ? 'selected':'' }}>Terlama</option>
            <option value="newest"    {{ ($filter??'')=='newest'    ? 'selected':'' }}>Terbaru</option>
        </select>
        <button type="submit"
                style="height:44px;padding:0 20px;background:#7831dc;color:#fff;border:none;border-radius:12px;
                       font-size:14px;font-weight:600;cursor:pointer;font-family:'Manrope',sans-serif;transition:all 150ms;"
                onmouseover="this.style.background='#5e26ac'" onmouseout="this.style.background='#7831dc'">
            Cari
        </button>
        @if(($search??'')||($filter??''))
        <a href="{{ route('admin.partners.index') }}"
           style="height:44px;padding:0 20px;display:inline-flex;align-items:center;background:#fff;color:#475569;
                  border:1px solid #e2e8f0;border-radius:12px;font-size:14px;font-weight:600;text-decoration:none;transition:background 150ms;"
           onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
            Reset
        </a>
        @endif
    </form>

    {{-- Table --}}
    <div style="background:#fff;border:1px solid #f1f5f9;border-radius:16px;overflow:hidden;
                box-shadow:0 1px 3px 0 rgba(15,23,42,.03);">
        <div class="overflow-x-auto">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:1px solid #f1f5f9;">
                        <th style="padding:12px 20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;text-align:left;width:56px;">No</th>
                        <th style="padding:12px 20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;text-align:left;">Logo</th>
                        <th style="padding:12px 20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;text-align:left;">Nama Partner</th>
                        <th style="padding:12px 20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;text-align:left;">Dibuat</th>
                        <th style="padding:12px 20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;text-align:left;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($partners as $index => $partner)
                    <tr style="border-bottom:1px solid #f1f5f9;transition:background 150ms;"
                        onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:14px 20px;font-size:14px;color:#94a3b8;">{{ $partners->firstItem() + $index }}</td>
                        <td style="padding:14px 20px;">
                            @php
                                $logoUrl = $partner->logo_url;
                                if (empty($logoUrl) || str_contains($logoUrl, 'via.placeholder.com')) {
                                    $logoUrl = 'https://placehold.co/44x44/f1f5f9/94a3b8?text=No+Img';
                                }
                            @endphp
                            <img src="{{ $logoUrl }}" alt="{{ $partner->name }}"
                                 style="width:44px;height:44px;border-radius:50%;object-fit:cover;border:1px solid #f1f5f9;">
                        </td>
                        <td style="padding:14px 20px;font-size:14px;font-weight:600;color:#1e293b;">{{ $partner->name }}</td>
                        <td style="padding:14px 20px;font-size:13px;color:#94a3b8;">
                            {{ \Carbon\Carbon::parse($partner->created_at)->format('d M Y') }}
                        </td>
                        <td style="padding:14px 20px;">
                            <div style="display:flex;gap:6px;align-items:center;">
                                <a href="{{ route('admin.partners.edit', $partner->id) }}"
                                   title="Edit"
                                   style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;
                                          background:#f1f5f9;border-radius:8px;text-decoration:none;transition:background 150ms;"
                                   onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                                    <svg width="14" height="14" fill="none" stroke="#475569" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus partner ini secara permanen?');" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
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
                        <td colspan="5" style="padding:56px 24px;text-align:center;color:#94a3b8;font-size:14px;">
                            Belum ada partner. Klik "Tambah Partner" untuk menambahkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($partners->hasPages())
        <div style="padding:16px 20px;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:between;flex-wrap:wrap;gap:12px;">
            <p style="font-size:13px;color:#94a3b8;flex:1;">
                Menampilkan <span style="color:#1e293b;font-weight:600;">{{ $partners->firstItem() }}</span>
                – <span style="color:#1e293b;font-weight:600;">{{ $partners->lastItem() }}</span>
                dari <span style="color:#1e293b;font-weight:600;">{{ $partners->total() }}</span> data
            </p>
            <div style="display:flex;gap:4px;align-items:center;">
                @if($partners->onFirstPage())
                    <span style="padding:6px 12px;font-size:13px;color:#cbd5e1;background:#f8fafc;border:1px solid #f1f5f9;border-radius:8px;cursor:not-allowed;">‹ Prev</span>
                @else
                    <a href="{{ $partners->appends(['search'=>$search,'filter'=>$filter])->previousPageUrl() }}"
                       style="padding:6px 12px;font-size:13px;color:#475569;background:#fff;border:1px solid #e2e8f0;border-radius:8px;text-decoration:none;transition:all 150ms;"
                       onmouseover="this.style.background='#f3ebfe';this.style.color='#8436f2';this.style.borderColor='#c6a3f9';"
                       onmouseout="this.style.background='#fff';this.style.color='#475569';this.style.borderColor='#e2e8f0';">‹ Prev</a>
                @endif

                @foreach($partners->getUrlRange(1, $partners->lastPage()) as $page => $url)
                    @if($page == $partners->currentPage())
                        <span style="padding:6px 12px;font-size:13px;font-weight:700;color:#fff;background:#ad78f6;border:1px solid #ad78f6;border-radius:8px;">{{ $page }}</span>
                    @else
                        <a href="{{ $partners->appends(['search'=>$search,'filter'=>$filter])->url($page) }}"
                           style="padding:6px 12px;font-size:13px;color:#475569;background:#fff;border:1px solid #e2e8f0;border-radius:8px;text-decoration:none;transition:all 150ms;"
                           onmouseover="this.style.background='#f3ebfe';this.style.color='#8436f2';this.style.borderColor='#c6a3f9';"
                           onmouseout="this.style.background='#fff';this.style.color='#475569';this.style.borderColor='#e2e8f0';">{{ $page }}</a>
                    @endif
                @endforeach

                @if($partners->hasMorePages())
                    <a href="{{ $partners->appends(['search'=>$search,'filter'=>$filter])->nextPageUrl() }}"
                       style="padding:6px 12px;font-size:13px;color:#475569;background:#fff;border:1px solid #e2e8f0;border-radius:8px;text-decoration:none;transition:all 150ms;"
                       onmouseover="this.style.background='#f3ebfe';this.style.color='#8436f2';this.style.borderColor='#c6a3f9';"
                       onmouseout="this.style.background='#fff';this.style.color='#475569';this.style.borderColor='#e2e8f0';">Next ›</a>
                @else
                    <span style="padding:6px 12px;font-size:13px;color:#cbd5e1;background:#f8fafc;border:1px solid #f1f5f9;border-radius:8px;cursor:not-allowed;">Next ›</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
