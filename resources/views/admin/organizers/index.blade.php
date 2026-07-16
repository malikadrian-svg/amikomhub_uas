@extends('layouts.admin')

@section('title', 'Kelola Organizer')

@section('content')
<div>

    {{-- Page Header --}}
    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:28px;gap:16px;flex-wrap:wrap;">
        <div>
            <h1 style="font-size:24px;font-weight:700;color:#0f172a;letter-spacing:-0.02em;margin-bottom:4px;">
                Kelola Organizer
            </h1>
            <p style="font-size:14px;color:#475569;">
                Daftar {{ $totalOrganizers }} organizer (HIMA/Kepanitiaan) terdaftar di platform.
            </p>
        </div>

        {{-- Tombol Tambah Organizer (trigger modal) --}}
        <button onclick="document.getElementById('addOrganizerModal').style.display='flex'"
            style="height:40px;padding:0 20px;background:#8436f2;color:#fff;border:none;border-radius:10px;
                   font-size:13px;font-weight:600;cursor:pointer;transition:all 150ms;display:flex;align-items:center;gap:8px;"
            onmouseover="this.style.background='#7831dc'" onmouseout="this.style.background='#8436f2'">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Organizer
        </button>
    </div>

    {{-- Search --}}
    <div style="margin-bottom:20px;">
        <form method="GET" action="{{ route('admin.organizers.index') }}">
            <div style="position:relative;max-width:360px;">
                <svg width="15" height="15" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24"
                     style="position:absolute;left:14px;top:50%;transform:translateY(-50%);">
                    <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                </svg>
                <input type="text" name="search" value="{{ $search ?? '' }}"
                       placeholder="Cari nama atau email organizer..."
                       style="width:100%;height:40px;padding:0 14px 0 38px;border:1px solid #e2e8f0;border-radius:10px;
                              font-size:13px;font-family:'Manrope',sans-serif;color:#1e293b;background:#fff;outline:none;transition:border 150ms;"
                       onfocus="this.style.borderColor='#9d5ef5'" onblur="this.style.borderColor='#e2e8f0'">
            </div>
        </form>
    </div>

    {{-- Organizer Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($organizers as $org)
        <div style="background:#fff;border:1px solid #f1f5f9;border-radius:16px;padding:22px;
                    box-shadow:0 1px 3px 0 rgba(15,23,42,.03);transition:box-shadow 150ms;"
             onmouseover="this.style.boxShadow='0 4px 12px rgba(15,23,42,.07)'" onmouseout="this.style.boxShadow='0 1px 3px 0 rgba(15,23,42,.03)'">

            {{-- Header --}}
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:18px;">
                <div style="width:44px;height:44px;border-radius:999px;background:#f3ebfe;color:#5e26ac;
                            font-size:15px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    {{ $org->initials }}
                </div>
                <div style="min-width:0;flex:1;">
                    <p style="font-size:14px;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $org->name }}
                    </p>
                    <p style="font-size:11px;color:#94a3b8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-top:1px;">
                        {{ $org->email }}
                    </p>
                </div>
                <span style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:999px;
                             background:#fffbeb;color:#b45309;border:1px solid #fde68a;flex-shrink:0;">
                    Organizer
                </span>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-3 gap-3" style="margin-bottom:18px;">
                <div style="background:#f8fafc;border-radius:10px;padding:10px;text-align:center;">
                    <p style="font-size:18px;font-weight:700;color:#0f172a;">{{ $org->stats['total_events'] }}</p>
                    <p style="font-size:10px;color:#94a3b8;font-weight:600;margin-top:2px;">Event</p>
                </div>
                <div style="background:#f8fafc;border-radius:10px;padding:10px;text-align:center;">
                    <p style="font-size:18px;font-weight:700;color:#15803d;">{{ $org->stats['approved_events'] }}</p>
                    <p style="font-size:10px;color:#94a3b8;font-weight:600;margin-top:2px;">Approved</p>
                </div>
                <div style="background:#f8fafc;border-radius:10px;padding:10px;text-align:center;">
                    <p style="font-size:18px;font-weight:700;color:#0f172a;">{{ $org->stats['tickets_sold'] }}</p>
                    <p style="font-size:10px;color:#94a3b8;font-weight:600;margin-top:2px;">Tiket</p>
                </div>
            </div>

            {{-- Revenue --}}
            <div style="background:#f3ebfe;border-radius:10px;padding:12px 14px;margin-bottom:16px;">
                <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#7831dc;margin-bottom:4px;">
                    Total Pendapatan
                </p>
                <p style="font-size:18px;font-weight:700;color:#491e85;letter-spacing:-0.02em;">
                    Rp {{ number_format($org->stats['total_revenue'], 0, ',', '.') }}
                </p>
            </div>

            {{-- Terdaftar sejak --}}
            <p style="font-size:11px;color:#94a3b8;margin-bottom:16px;">
                Terdaftar: {{ $org->created_at->format('d M Y') }}
            </p>

            {{-- Actions --}}
            <div style="display:flex;gap:8px;">
                <form method="POST" action="{{ route('admin.organizers.demote', $org->id) }}" style="flex:1;">
                    @csrf
                    <button type="submit"
                        style="width:100%;height:34px;background:#fff1f2;color:#be123c;border:1px solid #fecdd3;
                               border-radius:8px;font-size:11px;font-weight:700;cursor:pointer;transition:all 150ms;"
                        onclick="return confirm('Yakin ingin merevoke akses organizer {{ addslashes($org->name) }}?')"
                        onmouseover="this.style.background='#ffe4e6'" onmouseout="this.style.background='#fff1f2'">
                        Revoke Akses
                    </button>
                </form>
            </div>
        </div>
        @empty
            <div class="col-span-3" style="background:#f8fafc;border:1px dashed #e2e8f0;border-radius:16px;padding:60px 24px;text-align:center;">
                <svg width="48" height="48" fill="none" stroke="#94a3b8" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>
                </svg>
                <p style="font-size:15px;font-weight:600;color:#475569;">Belum ada organizer terdaftar</p>
                <p style="font-size:13px;color:#94a3b8;margin-top:6px;">Tambahkan organizer pertama dengan tombol di atas.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($organizers->hasPages())
        <div style="margin-top:24px;">{{ $organizers->links() }}</div>
    @endif

</div>

{{-- Modal Tambah Organizer --}}
<div id="addOrganizerModal"
     style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.3);z-index:50;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:20px;padding:32px;width:100%;max-width:440px;
                box-shadow:0 10px 15px -3px rgba(15,23,42,0.1);margin:16px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
            <h2 style="font-size:18px;font-weight:700;color:#0f172a;letter-spacing:-0.02em;">Tambah Akun Organizer</h2>
            <button onclick="document.getElementById('addOrganizerModal').style.display='none'"
                style="width:32px;height:32px;border-radius:8px;border:1px solid #e2e8f0;background:#f8fafc;cursor:pointer;
                       display:flex;align-items:center;justify-content:center;transition:all 150ms;"
                onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                <svg width="14" height="14" fill="none" stroke="#475569" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.organizers.store') }}">
            @csrf
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:12px;font-weight:700;color:#1e293b;margin-bottom:6px;">
                    Nama Organisasi / HIMA
                </label>
                <input type="text" name="name" required placeholder="e.g. HIMA Teknik Informatika"
                       style="width:100%;height:44px;padding:0 16px;border:1px solid #e2e8f0;border-radius:12px;
                              font-size:14px;font-family:'Manrope',sans-serif;color:#1e293b;outline:none;transition:border 150ms;"
                       onfocus="this.style.borderColor='#9d5ef5';this.style.boxShadow='0 0 0 4px #f3ebfe';"
                       onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none';">
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:12px;font-weight:700;color:#1e293b;margin-bottom:6px;">
                    Email Login
                </label>
                <input type="email" name="email" required placeholder="hima@amikom.ac.id"
                       style="width:100%;height:44px;padding:0 16px;border:1px solid #e2e8f0;border-radius:12px;
                              font-size:14px;font-family:'Manrope',sans-serif;color:#1e293b;outline:none;transition:border 150ms;"
                       onfocus="this.style.borderColor='#9d5ef5';this.style.boxShadow='0 0 0 4px #f3ebfe';"
                       onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none';">
            </div>
            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:12px;font-weight:700;color:#1e293b;margin-bottom:6px;">
                    Password
                </label>
                <input type="password" name="password" required placeholder="Minimal 8 karakter"
                       style="width:100%;height:44px;padding:0 16px;border:1px solid #e2e8f0;border-radius:12px;
                              font-size:14px;font-family:'Manrope',sans-serif;color:#1e293b;outline:none;transition:border 150ms;"
                       onfocus="this.style.borderColor='#9d5ef5';this.style.boxShadow='0 0 0 4px #f3ebfe';"
                       onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none';">
            </div>
            <div style="display:flex;gap:10px;">
                <button type="button" onclick="document.getElementById('addOrganizerModal').style.display='none'"
                    style="flex:1;height:40px;background:#f8fafc;color:#475569;border:1px solid #e2e8f0;border-radius:10px;
                           font-size:13px;font-weight:600;cursor:pointer;transition:all 150ms;"
                    onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                    Batal
                </button>
                <button type="submit"
                    style="flex:1;height:40px;background:#8436f2;color:#fff;border:none;border-radius:10px;
                           font-size:13px;font-weight:600;cursor:pointer;transition:all 150ms;"
                    onmouseover="this.style.background='#7831dc'" onmouseout="this.style.background='#8436f2'">
                    Buat Akun
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
