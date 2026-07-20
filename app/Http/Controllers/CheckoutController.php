<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function create(Event $event)
    {
        // Blok akses jika event sudah selesai
        if (Carbon::parse($event->date)->isPast()) {
            return redirect()->route('events.show', $event->id)
                ->with('error', 'Pembelian tiket tidak tersedia. Event ini telah selesai.');
        }

        // Mengambil daftar kategori untuk keperluan menu footer
        $categories = Category::all();

        return view('checkout.create', compact('event', 'categories'));
    }

    public function store(Request $request, Event $event)
    {
        // 1. Blok jika event sudah selesai
        if (Carbon::parse($event->date)->isPast()) {
            return back()->with('error', 'Pembelian tiket tidak tersedia. Event ini telah selesai.');
        }

        // 2. Validasi Input Kredensial Pelanggan
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        // 3. Cegah Check-out Jika Tiket Habis
        if ($event->stock <= 0) {
            return back()->with('error', 'Mohon maaf, tiket untuk acara ini sudah habis.');
        }

        // ─── FREE EVENT BYPASS ──────────────────────────────────────────────────
        // Jika harga tiket adalah Rp 0, lewati Midtrans sepenuhnya
        if ($event->price == 0) {
            return $this->processFreeTransaction($request, $event);
        }
        // ───────────────────────────────────────────────────────────────────────

        // 3. Generate Kode TRX (Unik)
        $orderId = 'TRX-' . time() . '-' . Str::random(5);
        $totalPrice = $event->price + 5000; // Menambahkan biaya admin (dummy)

        // 4. Merekam Transaksi ke Database
        $transaction = Transaction::create([
            'event_id' => $event->id,
            'order_id' => $orderId,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'total_price' => $totalPrice,
            'status' => 'Pending', // Status Awal
        ]);

        // --- INTEGRASI SNAP MIDTRANS ---
        // Konfigurasi Kredensial Environment Midtrans
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false; // Mode Sandbox!
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Susun Paket Array Data Transaksi
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name,
                'email' => $request->customer_email,
                'phone' => $request->customer_phone,
            ],
        ];

        try {
            // Perintah Tembak Generate Snap Token
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Update rekaman kita bahwa transaksi terkait sudah memiliki
            // id token pelunasan
            $transaction->update(['snap_token' => $snapToken]);

            // Redirect ke halaman antarmuka pembayaran final pelanggan
            return redirect()->route('checkout.payment', $transaction->order_id);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran jaringan: ' . $e->getMessage());
        }
    }

    /**
     * Memproses transaksi bypass untuk event gratis (price = Rp 0).
     * Melewati Midtrans sepenuhnya: langsung buat transaksi 'Success',
     * kurangi stok dengan DB lock, kirim e-ticket, dan redirect ke halaman sukses.
     */
    private function processFreeTransaction(Request $request, Event $event)
    {
        // Keamanan berlapis: tolak jika endpoint ini dipanggil untuk event berbayar
        if ($event->price > 0) {
            return back()->with('error', 'Event ini berbayar dan tidak dapat diproses melalui jalur gratis.');
        }

        $transaction = null;

        try {
            DB::transaction(function () use ($request, $event, &$transaction) {
                // Kunci baris event untuk mencegah race condition (overselling)
                $activeEvent = Event::where('id', $event->id)->lockForUpdate()->first();

                if ($activeEvent->stock <= 0) {
                    throw new \Exception('Tiket event ini sudah habis terjual.');
                }

                // Kurangi stok secara atomik
                $activeEvent->decrement('stock');

                // Buat transaksi langsung dengan status 'Success'
                $transaction = Transaction::create([
                    'event_id'       => $activeEvent->id,
                    'order_id'       => 'FREE-' . time() . '-' . rand(1000, 9999),
                    'customer_name'  => $request->customer_name,
                    'customer_email' => $request->customer_email,
                    'customer_phone' => $request->customer_phone,
                    'total_price'    => 0,
                    'status'         => 'Success',
                    'snap_token'     => 'FREE_BYPASS',
                ]);

                // Kirim e-ticket via email
                try {
                    \Illuminate\Support\Facades\Mail::to($transaction->customer_email)->send(
                        new \App\Mail\EventTicketMail($transaction)
                    );
                } catch (\Exception $mailErr) {
                    Log::error('Gagal mengirim e-ticket event gratis: ' . $mailErr->getMessage());
                }
            });

            return redirect()->route('checkout.success', $transaction->order_id);

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function payment($order_id)
    {
        // Mengambil daftar kategori untuk keperluan menu footer
        $categories = \App\Models\Category::all();
        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();

        return view('checkout.payment', compact('transaction', 'categories'));
    }

    public function success($order_id)
    {
        // Mengambil daftar kategori untuk keperluan menu footer
        $categories = \App\Models\Category::all();
        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();

        // ─── FREE BYPASS: lewati pengecekan Midtrans untuk tiket gratis ───────
        // Transaksi gratis sudah langsung berstatus 'Success', tidak perlu dicek ke API Midtrans
        if ($transaction->snap_token === 'FREE_BYPASS') {
            return view('checkout.success', compact('transaction', 'categories'));
        }
        // ────────────────────────────────────────────────────────────────────────

        // Konfigurasi Midtrans untuk mengecek status transaksi langsung ke API
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        try {
            // Mengecek status pesanan secara mandiri (Bypass)
            $status = \Midtrans\Transaction::status($order_id);

            if ($status) {
                // Mengambil nilai status transaksi
                $trx_status = is_array($status) ? ($status['transaction_status'] ?? '') : ($status->transaction_status ?? '');

                // Jika API Midtrans mengonfirmasi bahwa transaksi telah berhasil (settlement / capture)
                if (in_array($trx_status, ['settlement', 'capture'])) {
                    // Hanya lakukan update jika status di database lokal masih 'pending' (indikasi Webhook tidak masuk)
                    if (strtolower($transaction->status) === 'pending') {
                        $transaction->update(['status' => 'success']);

                        if ($transaction->event && $transaction->event->stock > 0) {
                            $transaction->event->stock = $transaction->event->stock - 1;
                            $transaction->event->save();

                            try {
                                \Illuminate\Support\Facades\Mail::to($transaction->customer_email)->send(
                                    new \App\Mail\EventTicketMail($transaction)
                                );
                            } catch (\Exception $e) {
                                \Log::error('Gagal mengirim email ETicket secara manual (Bypass): ' . $e->getMessage());
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Jika terjadi error dari API Midtrans (transaksi tidak valid), kembalikan ke beranda
            return redirect()->route('home')->with('error', 'Transaksi tidak ditemukan atau gagal diproses oleh sistem pembayaran.');
        }

        return view('checkout.success', compact('transaction', 'categories'));
    }
}
