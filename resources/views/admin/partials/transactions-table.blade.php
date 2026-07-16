<div class="overflow-x-auto">
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#f8fafc;border-bottom:1px solid #f1f5f9;">
                <th style="padding:11px 20px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;text-align:left;white-space:nowrap;">Tgl Transaksi</th>
                <th style="padding:11px 20px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;text-align:left;">Pembeli</th>
                <th style="padding:11px 20px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;text-align:left;">Event</th>
                <th style="padding:11px 20px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;text-align:left;">Status</th>
                <th style="padding:11px 20px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;text-align:right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $trx)
                <tr style="border-bottom:1px solid #f1f5f9;transition:background 150ms;"
                    onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                    <td style="padding:13px 20px;">
                        <p style="font-size:12px;color:#475569;white-space:nowrap;">{{ $trx->created_at->format('d M Y') }}</p>
                        <p style="font-size:11px;color:#94a3b8;font-family:monospace;margin-top:2px;">{{ $trx->order_id }}</p>
                    </td>
                    <td style="padding:13px 20px;">
                        <p style="font-size:13px;font-weight:600;color:#1e293b;max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $trx->customer_name }}</p>
                        <p style="font-size:11px;color:#94a3b8;max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $trx->customer_email }}</p>
                    </td>
                    <td style="padding:13px 20px;font-size:13px;color:#475569;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        {{ $trx->event->title ?? '-' }}
                    </td>
                    <td style="padding:13px 20px;">
                        @if(in_array(strtolower($trx->status), ['settlement','success']))
                            <span style="display:inline-flex;padding:2px 9px;background:#f0fdf4;color:#15803d;border-radius:6px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.04em;border:1px solid #bbf7d0;">Lunas</span>
                        @elseif(strtolower($trx->status) === 'pending')
                            <span style="display:inline-flex;padding:2px 9px;background:#fffbeb;color:#b45309;border-radius:6px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.04em;border:1px solid #fde68a;">Pending</span>
                        @else
                            <span style="display:inline-flex;padding:2px 9px;background:#fff1f2;color:#be123c;border-radius:6px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.04em;border:1px solid #fecdd3;">{{ $trx->status }}</span>
                        @endif
                    </td>
                    <td style="padding:13px 20px;text-align:right;font-size:13px;font-weight:700;color:#0f172a;white-space:nowrap;">
                        Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding:40px 24px;text-align:center;color:#94a3b8;font-size:13px;">
                        Belum ada transaksi tercatat.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
