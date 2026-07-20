@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div>
    {{-- ─── PAGE HEADER ──────────────────────────────────────────────────────── --}}
    <div style="margin-bottom:28px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px;">
        <div style="flex: 1; min-width: 200px;">
            @if($role === 'superadmin')
                <h1 style="font-size:24px;font-weight:700;color:#0f172a;letter-spacing:-0.02em;margin-bottom:4px; font-family:'Manrope',sans-serif;">
                    Dashboard Superadmin
                </h1>
                <p style="font-size:14px;color:#475569;">
                    Ringkasan platform secara keseluruhan &amp; antrian persetujuan event.
                </p>
            @else
                <h1 style="font-size:24px;font-weight:700;color:#0f172a;letter-spacing:-0.02em;margin-bottom:4px; font-family:'Manrope',sans-serif;">
                    Dashboard Organizer
                </h1>
                <p style="font-size:14px;color:#475569;">
                    Statistik penjualan &amp; performa event milik <strong>{{ auth()->user()->name }}</strong>.
                </p>
            @endif
        </div>
        
        {{-- Drop-down Filter Rentang Waktu --}}
        <div>
            <select id="timeRangeFilter" style="height:40px; padding: 0 16px; border-radius:12px; border:1px solid #e2e8f0; background:#ffffff; color:#1e293b; font-family:'Manrope',sans-serif; font-size:13px; font-weight:600; cursor:pointer; outline:none; transition: all 150ms ease-out;"
                    onmouseover="this.style.borderColor='#94a3b8'"
                    onmouseout="if(document.activeElement !== this) this.style.borderColor='#e2e8f0'"
                    onfocus="this.style.borderColor='#9d5ef5'; this.style.boxShadow='0 0 0 4px #f3ebfe'"
                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                <option value="7_days">7 Hari Terakhir</option>
                <option value="30_days">30 Hari Terakhir</option>
                <option value="1_year" selected>1 Tahun Terakhir</option>
            </select>
        </div>
    </div>

    {{-- ─── STATS GRID ──────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5" style="margin-bottom:28px;">

        {{-- Total Pendapatan --}}
        <div style="background:#fff;border:1px solid #f1f5f9;border-radius:16px;padding:22px;
                    box-shadow:0 1px 3px 0 rgba(15,23,42,.03);">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;">
                <div style="width:38px;height:38px;background:#f1f5f9;border-radius:10px;
                            display:flex;align-items:center;justify-content:center;">
                    <svg width="18" height="18" fill="none" stroke="#475569" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin-bottom:6px;">
                Total Pendapatan
            </p>
            <p id="stat-total-revenue" style="font-size:22px;font-weight:700;color:#0f172a;letter-spacing:-0.02em;">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </p>
        </div>

        {{-- Tiket Terjual --}}
        <div style="background:#fff;border:1px solid #f1f5f9;border-radius:16px;padding:22px;
                    box-shadow:0 1px 3px 0 rgba(15,23,42,.03);">
            <div style="width:38px;height:38px;background:#f1f5f9;border-radius:10px;
                        display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
                <svg width="18" height="18" fill="none" stroke="#475569" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
            </div>
            <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin-bottom:6px;">
                Tiket Terjual
            </p>
            <p id="stat-tickets-sold" style="font-size:22px;font-weight:700;color:#0f172a;letter-spacing:-0.02em;">
                {{ number_format($ticketsSold, 0, ',', '.') }}
            </p>
        </div>

        {{-- Event Aktif --}}
        <div style="background:#fff;border:1px solid #f1f5f9;border-radius:16px;padding:22px;
                    box-shadow:0 1px 3px 0 rgba(15,23,42,.03);">
            <div style="width:38px;height:38px;background:#f1f5f9;border-radius:10px;
                        display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
                <svg width="18" height="18" fill="none" stroke="#475569" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin-bottom:6px;">
                Event Aktif
            </p>
            <p id="stat-active-events" style="font-size:22px;font-weight:700;color:#0f172a;letter-spacing:-0.02em;">{{ $activeEvents }}</p>
        </div>

        {{-- Stat ke-4: berbeda per role --}}
        @if($role === 'superadmin')
            {{-- Jumlah Organizer Aktif --}}
            <div style="background:#fff;border:1px solid #f1f5f9;border-radius:16px;padding:22px;
                        box-shadow:0 1px 3px 0 rgba(15,23,42,.03);">
                <div style="width:38px;height:38px;background:#f1f5f9;border-radius:10px;
                            display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
                    <svg width="18" height="18" fill="none" stroke="#475569" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin-bottom:6px;">
                    Organizer Aktif
                </p>
                <p id="stat-active-organizers" style="font-size:22px;font-weight:700;color:#0f172a;letter-spacing:-0.02em;">{{ $activeOrganizers }}</p>
            </div>
        @else
            {{-- Pesanan Pending untuk Organizer --}}
            <div style="background:#fff;border:1px solid #f1f5f9;border-radius:16px;padding:22px;
                        box-shadow:0 1px 3px 0 rgba(15,23,42,.03);">
                <div style="width:38px;height:38px;background:#f1f5f9;border-radius:10px;
                            display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
                    <svg width="18" height="18" fill="none" stroke="#475569" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin-bottom:6px;">
                    Pesanan Pending
                </p>
                <p id="stat-pending-orders" style="font-size:22px;font-weight:700;color:#0f172a;letter-spacing:-0.02em;">{{ $pendingOrders }}</p>
            </div>
        @endif

    </div>

    {{-- ─── CHARTS SECTION ─────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" style="margin-bottom:28px;">
        {{-- Chart 1 (Line Chart) --}}
        <div style="background:#ffffff; border:1px solid #f1f5f9; border-radius:16px; padding:24px; box-shadow:0 1px 3px 0 rgba(15,23,42,.03);">
            <div style="margin-bottom:20px;">
                <h3 style="font-size:16px; font-weight:600; color:#1e293b; letter-spacing:-0.02em; font-family:'Manrope',sans-serif;">
                    @if($role === 'superadmin') Pertumbuhan Pengguna Baru @else Tren Pendapatan Penjualan @endif
                </h3>
                <p style="font-size:12px; color:#475569; margin-top:2px;">
                    @if($role === 'superadmin') Tren registrasi pengguna baru @else Grafik total pendapatan penjualan @endif
                </p>
            </div>
            <div style="height:260px; position:relative;">
                <canvas id="lineChart"></canvas>
            </div>
        </div>

        {{-- Chart 2 (Bar Chart) --}}
        <div style="background:#ffffff; border:1px solid #f1f5f9; border-radius:16px; padding:24px; box-shadow:0 1px 3px 0 rgba(15,23,42,.03);">
            <div style="margin-bottom:20px;">
                <h3 style="font-size:16px; font-weight:600; color:#1e293b; letter-spacing:-0.02em; font-family:'Manrope',sans-serif;">
                    @if($role === 'superadmin') Perkembangan Penyelenggaraan Event @else Penjualan Tiket per Event @endif
                </h3>
                <p style="font-size:12px; color:#475569; margin-top:2px;">
                    @if($role === 'superadmin') Jumlah event baru yang dibuat @else Jumlah tiket terjual per acara @endif
                </p>
            </div>
            <div style="height:260px; position:relative;">
                <canvas id="barChart"></canvas>
            </div>
        </div>
    </div>

    {{-- ─── CONDITIONAL SECTIONS ──────────────────────────────────────────────── --}}
    @if($role === 'superadmin')
        {{-- ══ SUPERADMIN: Antrian Approval + Transaksi Terbaru ══ --}}

        {{-- Antrian Approval Event --}}
        @if(isset($pendingEvents) && $pendingEvents->count() > 0)
        <div style="background:#fff;border:1px solid #f1f5f9;border-radius:16px;overflow:hidden;
                    box-shadow:0 1px 3px 0 rgba(15,23,42,.03);margin-bottom:24px;">
            <div style="padding:18px 24px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:8px;height:8px;border-radius:999px;background:#f59e0b;animation:pulse 2s infinite;"></div>
                    <div>
                        <h2 style="font-size:15px;font-weight:700;color:#0f172a;letter-spacing:-0.02em;">Antrian Persetujuan Event</h2>
                        <p style="font-size:12px;color:#94a3b8;margin-top:1px;">{{ $pendingEvents->count() }} event menunggu review Anda</p>
                    </div>
                </div>
                <a href="{{ route('admin.approvals.index') }}"
                   style="font-size:12px;font-weight:600;color:#8436f2;text-decoration:none;transition:color 150ms;"
                   onmouseover="this.style.color='#7831dc'" onmouseout="this.style.color='#8436f2'">
                    Lihat Semua →
                </a>
            </div>

            <div style="padding:16px 24px;display:flex;flex-direction:column;gap:12px;">
                @foreach($pendingEvents->take(5) as $pending)
                <div style="display:flex;align-items:center;gap:14px;padding:14px 16px;border-radius:12px;border:1px solid #f1f5f9;background:#fafafa;transition:background 150ms;"
                     onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fafafa'">
                    {{-- Thumbnail --}}
                    <div style="width:48px;height:48px;border-radius:10px;overflow:hidden;flex-shrink:0;background:#f1f5f9;">
                        @if($pending->poster_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($pending->poster_path))
                            <img src="{{ asset('storage/' . $pending->poster_path) }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                <svg width="20" height="20" fill="none" stroke="#94a3b8" stroke-width="1.75" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div style="flex:1;min-width:0;">
                        <p style="font-size:13px;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $pending->title }}
                        </p>
                        <div style="display:flex;align-items:center;gap:8px;margin-top:4px;">
                            <span style="font-size:11px;color:#94a3b8;font-weight:500;">
                                {{ $pending->organizer->name ?? 'Organizer Tidak Diketahui' }}
                            </span>
                            <span style="width:3px;height:3px;border-radius:999px;background:#e2e8f0;"></span>
                            <span style="font-size:11px;color:#94a3b8;">
                                {{ \Carbon\Carbon::parse($pending->date)->format('d M Y') }}
                            </span>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div style="display:flex;gap:6px;flex-shrink:0;">
                        <form method="POST" action="{{ route('admin.approvals.approve', $pending->id) }}">
                            @csrf
                            <button type="submit"
                                style="height:32px;padding:0 14px;background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;
                                       border-radius:8px;font-size:11px;font-weight:700;cursor:pointer;transition:all 150ms;"
                                onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fdf4'">
                                ✓ Approve
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.approvals.reject', $pending->id) }}">
                            @csrf
                            <button type="submit"
                                style="height:32px;padding:0 14px;background:#fff1f2;color:#be123c;border:1px solid #fecdd3;
                                       border-radius:8px;font-size:11px;font-weight:700;cursor:pointer;transition:all 150ms;"
                                onmouseover="this.style.background='#ffe4e6'" onmouseout="this.style.background='#fff1f2'">
                                ✕ Tolak
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
            <div style="background:#f8fafc;border:1px dashed #e2e8f0;border-radius:16px;padding:32px 24px;text-align:center;margin-bottom:24px;">
                <svg width="36" height="36" fill="none" stroke="#94a3b8" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 12px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p style="font-size:14px;font-weight:600;color:#475569;">Tidak ada event yang menunggu persetujuan</p>
                <p style="font-size:12px;color:#94a3b8;margin-top:4px;">Semua event sudah ditinjau.</p>
            </div>
        @endif

        {{-- Transaksi Terbaru (Superadmin - Global) --}}
        <div style="background:#fff;border:1px solid #f1f5f9;border-radius:16px;overflow:hidden;
                    box-shadow:0 1px 3px 0 rgba(15,23,42,.03);">
            <div style="padding:18px 24px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <h2 style="font-size:15px;font-weight:700;color:#0f172a;letter-spacing:-0.02em;">Transaksi Terbaru</h2>
                    <p style="font-size:12px;color:#94a3b8;margin-top:1px;">Seluruh platform · 5 transaksi terbaru</p>
                </div>
                <a href="{{ route('admin.transactions.index') }}"
                   style="font-size:12px;font-weight:600;color:#8436f2;text-decoration:none;"
                   onmouseover="this.style.color='#7831dc'" onmouseout="this.style.color='#8436f2'">
                    Lihat Semua →
                </a>
            </div>
            @include('admin.partials.transactions-table', ['transactions' => $recentTransactions])
        </div>

    @else
        {{-- ══ ORGANIZER: Daftar Event Saya + Transaksi ══ --}}

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- Event Saya --}}
            <div class="lg:col-span-7">
                <div style="background:#fff;border:1px solid #f1f5f9;border-radius:16px;overflow:hidden;
                            box-shadow:0 1px 3px 0 rgba(15,23,42,.03);">
                    <div style="padding:18px 24px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
                        <div>
                            <h2 style="font-size:15px;font-weight:700;color:#0f172a;letter-spacing:-0.02em;">Event Saya</h2>
                            <p style="font-size:12px;color:#94a3b8;margin-top:1px;">5 event terbaru Anda</p>
                        </div>
                        <a href="{{ route('admin.events.index') }}"
                           style="font-size:12px;font-weight:600;color:#8436f2;text-decoration:none;"
                           onmouseover="this.style.color='#7831dc'" onmouseout="this.style.color='#8436f2'">
                            Kelola Semua →
                        </a>
                    </div>

                    <div style="padding:16px 20px;display:flex;flex-direction:column;gap:10px;">
                        @forelse($myEvents as $myEvent)
                            @php $badge = $myEvent->status_badge; @endphp
                            <div style="display:flex;align-items:center;gap:12px;padding:12px 14px;border-radius:10px;border:1px solid #f1f5f9;">
                                {{-- Thumbnail --}}
                                <div style="width:44px;height:44px;border-radius:8px;overflow:hidden;flex-shrink:0;background:#f1f5f9;">
                                    @if($myEvent->poster_path)
                                        <img src="{{ asset('storage/' . $myEvent->poster_path) }}" style="width:100%;height:100%;object-fit:cover;">
                                    @else
                                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                            <svg width="18" height="18" fill="none" stroke="#94a3b8" stroke-width="1.75" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div style="flex:1;min-width:0;">
                                    <p style="font-size:13px;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        {{ $myEvent->title }}
                                    </p>
                                    <div style="display:flex;align-items:center;gap:6px;margin-top:4px;">
                                        <span style="font-size:10px;color:#94a3b8;">{{ \Carbon\Carbon::parse($myEvent->date)->format('d M Y') }}</span>
                                        <span style="font-size:10px;font-weight:700;padding:1px 7px;border-radius:999px;
                                                     background:{{ $badge['bg'] }};color:{{ $badge['text'] }};border:1px solid {{ $badge['border'] }};">
                                            {{ $badge['label'] }}
                                        </span>
                                    </div>
                                </div>
                                <div style="text-right;flex-shrink:0;">
                                    <p style="font-size:11px;font-weight:700;color:#0f172a;">{{ $myEvent->stock }} tiket</p>
                                    <p style="font-size:10px;color:#94a3b8;">tersisa</p>
                                </div>
                            </div>
                        @empty
                            <div style="padding:32px;text-align:center;">
                                <p style="font-size:13px;color:#94a3b8;">Belum ada event. <a href="{{ route('admin.events.create') }}" style="color:#8436f2;font-weight:600;text-decoration:none;">Buat event pertama →</a></p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Transaksi Terbaru Organizer --}}
            <div class="lg:col-span-5">
                <div style="background:#fff;border:1px solid #f1f5f9;border-radius:16px;overflow:hidden;
                            box-shadow:0 1px 3px 0 rgba(15,23,42,.03);">
                    <div style="padding:18px 24px;border-bottom:1px solid #f1f5f9;">
                        <h2 style="font-size:15px;font-weight:700;color:#0f172a;letter-spacing:-0.02em;">Transaksi Terbaru</h2>
                        <p style="font-size:12px;color:#94a3b8;margin-top:1px;">5 transaksi event Anda</p>
                    </div>
                    <div style="padding:12px 16px;display:flex;flex-direction:column;gap:8px;">
                        @forelse($recentTransactions as $trx)
                            <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 12px;border-radius:8px;border:1px solid #f8fafc;transition:background 150ms;"
                                 onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                                <div style="min-width:0;flex:1;">
                                    <p style="font-size:12px;font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        {{ $trx->customer_name }}
                                    </p>
                                    <p style="font-size:11px;color:#94a3b8;margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        {{ $trx->event->title ?? '-' }}
                                    </p>
                                </div>
                                <div style="text-align:right;flex-shrink:0;margin-left:12px;">
                                    <p style="font-size:12px;font-weight:700;color:#0f172a;">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</p>
                                    @if(in_array($trx->status, ['settlement','success','Success']))
                                        <span style="font-size:10px;font-weight:700;color:#15803d;">Lunas</span>
                                    @elseif($trx->status === 'pending')
                                        <span style="font-size:10px;font-weight:700;color:#b45309;">Pending</span>
                                    @else
                                        <span style="font-size:10px;font-weight:700;color:#be123c;">{{ $trx->status }}</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div style="padding:32px;text-align:center;">
                                <p style="font-size:13px;color:#94a3b8;">Belum ada transaksi.</p>
                            </div>
                        @endforelse
                    </div>
                    <div style="padding:12px 16px;border-top:1px solid #f1f5f9;text-align:center;">
                        <a href="{{ route('admin.transactions.index') }}"
                           style="font-size:12px;font-weight:600;color:#8436f2;text-decoration:none;">
                            Lihat Semua Transaksi →
                        </a>
                    </div>
                </div>
            </div>

        </div>

        {{-- Notif status event organizer --}}
        @php $pendingEventsOrg = \App\Models\Event::where('organizer_id', auth()->id())->pending()->count(); @endphp
        @if($pendingEventsOrg > 0)
        <div style="margin-top:20px;background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:14px 18px;display:flex;align-items:center;gap:12px;">
            <svg width="18" height="18" fill="none" stroke="#b45309" stroke-width="1.75" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p style="font-size:13px;font-weight:600;color:#92400e;">
                Anda memiliki <strong>{{ $pendingEventsOrg }} event</strong> yang sedang menunggu persetujuan Superadmin sebelum tampil di publik.
            </p>
        </div>
        @endif

    @endif

</div>

<style>
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timeRangeFilter = document.getElementById('timeRangeFilter');
        
        // Element references
        const statTotalRevenue = document.getElementById('stat-total-revenue');
        const statTicketsSold = document.getElementById('stat-tickets-sold');
        const statActiveEvents = document.getElementById('stat-active-events');
        const statActiveOrganizers = document.getElementById('stat-active-organizers');
        const statPendingOrders = document.getElementById('stat-pending-orders');

        // Chart instances
        let lineChartInstance = null;
        let barChartInstance = null;

        // Colors from design.md
        const violet500 = '#8436f2';
        const violet400 = '#9d5ef5';
        const violet200 = '#c6a3f9';
        const neutral600 = '#475569';
        const neutral800 = '#1e293b';

        // Function to fetch stats and update UI & Charts
        function updateDashboard(range) {
            fetch(`{{ route('admin.dashboard.api') }}?range=${range}`)
                .then(response => response.json())
                .then(res => {
                    if (res.status === 'success') {
                        const data = res.data;
                        
                        // 1. Update Card Values
                        if (statTotalRevenue) statTotalRevenue.textContent = data.cards.total_revenue;
                        if (statTicketsSold) statTicketsSold.textContent = data.cards.tickets_sold;
                        if (statActiveEvents) statActiveEvents.textContent = data.cards.active_events;
                        
                        @if($role === 'superadmin')
                            if (statActiveOrganizers) statActiveOrganizers.textContent = data.cards.active_organizers;
                        @else
                            if (statPendingOrders) statPendingOrders.textContent = data.cards.pending_orders;
                        @endif

                        // 2. Update Charts
                        const isSuperadmin = {{ $role === 'superadmin' ? 'true' : 'false' }};
                        
                        // Line Chart Update
                        const lineLabels = isSuperadmin ? data.charts.user_growth.labels : data.charts.revenue_growth.labels;
                        const lineData = isSuperadmin ? data.charts.user_growth.datasets : data.charts.revenue_growth.datasets;
                        
                        renderLineChart(lineLabels, lineData, isSuperadmin ? 'User Baru' : 'Pendapatan (Rp)');

                        // Bar Chart Update
                        const barLabels = isSuperadmin ? data.charts.event_growth.labels : data.charts.ticket_sales_per_event.labels;
                        const barData = isSuperadmin ? data.charts.event_growth.datasets : data.charts.ticket_sales_per_event.datasets;
                        
                        renderBarChart(barLabels, barData, isSuperadmin ? 'Event Baru' : 'Tiket Terjual');
                    }
                })
                .catch(err => console.error('Error fetching dashboard stats:', err));
        }

        // Render Line Chart
        function renderLineChart(labels, dataset, labelName) {
            const ctx = document.getElementById('lineChart').getContext('2d');
            
            if (lineChartInstance) {
                lineChartInstance.destroy();
            }

            // Create gradient
            const gradient = ctx.createLinearGradient(0, 0, 0, 260);
            gradient.addColorStop(0, 'rgba(132, 54, 242, 0.15)');
            gradient.addColorStop(1, 'rgba(132, 54, 242, 0.0)');

            lineChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: labelName,
                        data: dataset,
                        borderColor: violet500,
                        backgroundColor: gradient,
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: violet500,
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: violet500,
                        pointHoverBorderColor: '#ffffff',
                        pointHoverBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { family: 'Manrope', size: 11 },
                                color: neutral600
                            }
                        },
                        y: {
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                font: { family: 'Manrope', size: 11 },
                                color: neutral600,
                                precision: 0
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Render Bar Chart
        function renderBarChart(labels, dataset, labelName) {
            const ctx = document.getElementById('barChart').getContext('2d');
            
            if (barChartInstance) {
                barChartInstance.destroy();
            }

            barChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: labelName,
                        data: dataset,
                        backgroundColor: violet500,
                        hoverBackgroundColor: violet400,
                        borderRadius: 4,
                        maxBarThickness: 32
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { family: 'Manrope', size: 11 },
                                color: neutral600
                            }
                        },
                        y: {
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                font: { family: 'Manrope', size: 11 },
                                color: neutral600,
                                precision: 0
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Listen for filter changes
        timeRangeFilter.addEventListener('change', function() {
            updateDashboard(this.value);
        });

        // Initialize on page load
        updateDashboard('1_year');
    });
</script>
@endsection