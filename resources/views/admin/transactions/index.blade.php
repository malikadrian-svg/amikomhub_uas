@extends('layouts.admin')

@section('title', 'Laporan Transaksi')

@section('content')
<div>
    <!-- Page Header -->
    <div style="margin-bottom:24px;">
        <h1 style="font-size:24px;font-weight:700;color:#0f172a;letter-spacing:-0.02em;margin-bottom:4px;">Laporan Transaksi</h1>
        <p style="font-size:14px;color:#475569;">Riwayat seluruh transaksi pembelian tiket.</p>
    </div>

    {{-- Search & Filter --}}
    <form action="{{ route('admin.transactions.index') }}" method="GET"
          style="display:flex;gap:10px;align-items:center;margin-bottom:20px;flex-wrap:wrap;">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari Order ID, pembeli, atau event..."
               style="flex:1;min-width:200px;height:44px;padding:0 16px;border:1px solid #e2e8f0;border-radius:12px;
                      font-size:14px;color:#1e293b;background:#fff;outline:none;font-family:'Manrope',sans-serif;"
               onfocus="this.style.borderColor='#9d5ef5';this.style.boxShadow='0 0 0 4px #f3ebfe';"
               onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none';">
        <select name="filter"
                style="height:44px;padding:0 12px;border:1px solid #e2e8f0;border-radius:12px;
                       font-size:14px;color:#1e293b;background:#fff;outline:none;font-family:'Manrope',sans-serif;">
            <option value="">— Urutkan —</option>
            <option value="newest"     {{ ($filter??'')=='newest'     ?'selected':'' }}>Terbaru</option>
            <option value="oldest"     {{ ($filter??'')=='oldest'     ?'selected':'' }}>Terlama</option>
            <option value="price_desc" {{ ($filter??'')=='price_desc' ?'selected':'' }}>Tagihan Terbesar</option>
            <option value="price_asc"  {{ ($filter??'')=='price_asc'  ?'selected':'' }}>Tagihan Terkecil</option>
        </select>
        <button type="submit"
                style="height:44px;padding:0 20px;background:#7831dc;color:#fff;border:none;border-radius:12px;
                       font-size:14px;font-weight:600;cursor:pointer;font-family:'Manrope',sans-serif;transition:all 150ms;"
                onmouseover="this.style.background='#5e26ac'" onmouseout="this.style.background='#7831dc'">Cari</button>
        @if(($search??'')||($filter??''))
        <a href="{{ route('admin.transactions.index') }}"
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
                        <th style="padding:12px 20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;text-align:left;">Order ID</th>
                        <th style="padding:12px 20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;text-align:left;">Detail Pembeli</th>
                        <th style="padding:12px 20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;text-align:left;">Event</th>
                        <th style="padding:12px 20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;text-align:left;">Tgl Transaksi</th>
                        <th style="padding:12px 20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;text-align:left;">Status</th>
                        <th style="padding:12px 20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;text-align:right;">Total Tagihan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                    <tr style="border-bottom:1px solid #f1f5f9;transition:background 150ms;"
                        onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:14px 20px;">
                            <span style="font-family:monospace;font-size:12px;font-weight:700;padding:3px 10px;border-radius:6px;
                                  background:#f1f5f9;
                                  color:#475569;">
                                {{ $trx->order_id }}
                            </span>
                        </td>
                        <td style="padding:14px 20px;">
                            <p style="font-size:14px;font-weight:600;color:#1e293b;margin-bottom:2px;">{{ $trx->customer_name }}</p>
                            <p style="font-size:12px;color:#94a3b8;">{{ $trx->customer_email }}</p>
                            @if($trx->customer_phone)
                            <p style="font-size:12px;color:#94a3b8;">{{ $trx->customer_phone }}</p>
                            @endif
                        </td>
                        <td style="padding:14px 20px;font-size:14px;color:#475569;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ $trx->event->title ?? '-' }}
                        </td>
                        <td style="padding:14px 20px;font-size:13px;color:#94a3b8;white-space:nowrap;">
                            {{ $trx->created_at->format('d M Y, H:i') }}
                        </td>
                        <td style="padding:14px 20px;">
                            @if(in_array(strtolower($trx->status), ['settlement', 'success']))
                                <span style="display:inline-flex;padding:3px 10px;background:#f0fdf4;color:#15803d;border-radius:6px;font-size:11px;font-weight:700;text-transform:uppercase;border:1px solid #bbf7d0;">Lunas</span>
                            @elseif(strtolower($trx->status) === 'pending')
                                <span style="display:inline-flex;padding:3px 10px;background:#fffbeb;color:#b45309;border-radius:6px;font-size:11px;font-weight:700;text-transform:uppercase;border:1px solid #fde68a;">Pending</span>
                            @else
                                <span style="display:inline-flex;padding:3px 10px;background:#fff1f2;color:#be123c;border-radius:6px;font-size:11px;font-weight:700;text-transform:uppercase;border:1px solid #fecdd3;">{{ $trx->status }}</span>
                            @endif
                        </td>
                        <td style="padding:14px 20px;text-align:right;font-size:14px;font-weight:700;color:#0f172a;white-space:nowrap;">
                            Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding:56px 24px;text-align:center;color:#94a3b8;font-size:14px;">
                            Belum ada transaksi yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
        <div style="padding:16px 20px;border-top:1px solid #f1f5f9;display:flex;align-items:center;flex-wrap:wrap;gap:12px;">
            <p style="font-size:13px;color:#94a3b8;flex:1;">
                Menampilkan <span style="color:#1e293b;font-weight:600;">{{ $transactions->firstItem() }}</span>
                – <span style="color:#1e293b;font-weight:600;">{{ $transactions->lastItem() }}</span>
                dari <span style="color:#1e293b;font-weight:600;">{{ $transactions->total() }}</span> data
            </p>
            <div style="display:flex;gap:4px;align-items:center;">
                @if($transactions->onFirstPage())
                    <span style="padding:6px 12px;font-size:13px;color:#cbd5e1;background:#f8fafc;border:1px solid #f1f5f9;border-radius:8px;cursor:not-allowed;">‹ Prev</span>
                @else
                    <a href="{{ $transactions->appends(['search'=>$search,'filter'=>$filter])->previousPageUrl() }}"
                       style="padding:6px 12px;font-size:13px;color:#475569;background:#fff;border:1px solid #e2e8f0;border-radius:8px;text-decoration:none;"
                       onmouseover="this.style.background='#f3ebfe';this.style.color='#8436f2';"
                       onmouseout="this.style.background='#fff';this.style.color='#475569';">‹ Prev</a>
                @endif
                @foreach($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                    @if($page == $transactions->currentPage())
                        <span style="padding:6px 12px;font-size:13px;font-weight:700;color:#fff;background:#ad78f6;border:1px solid #ad78f6;border-radius:8px;">{{ $page }}</span>
                    @else
                        <a href="{{ $transactions->appends(['search'=>$search,'filter'=>$filter])->url($page) }}"
                           style="padding:6px 12px;font-size:13px;color:#475569;background:#fff;border:1px solid #e2e8f0;border-radius:8px;text-decoration:none;"
                           onmouseover="this.style.background='#f3ebfe';this.style.color='#8436f2';"
                           onmouseout="this.style.background='#fff';this.style.color='#475569';">{{ $page }}</a>
                    @endif
                @endforeach
                @if($transactions->hasMorePages())
                    <a href="{{ $transactions->appends(['search'=>$search,'filter'=>$filter])->nextPageUrl() }}"
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
