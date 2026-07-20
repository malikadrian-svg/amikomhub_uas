@extends('layouts.app')

@section('title', $transaction->snap_token === 'FREE_BYPASS' ? 'Pendaftaran Berhasil' : 'Pembayaran Berhasil')

@section('content')
<main class="max-w-3xl mx-auto px-6 py-24 text-center">
    <div class="bg-white rounded-2xl border border-neutral-200 p-12 shadow-sm inline-block w-full max-w-md">
        
        {{-- Success Checkmark Icon --}}
        <div class="w-16 h-16 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        @if($transaction->snap_token === 'FREE_BYPASS')
            {{-- Pesan khusus untuk event gratis --}}
            <h2 class="text-2xl font-extrabold text-neutral-900 mb-3">Pendaftaran Berhasil! 🎉</h2>
            <p class="text-neutral-500 text-xs font-semibold leading-relaxed mb-8">
                Selamat! Tiket gratis untuk pesanan <strong class="text-neutral-800">{{ $transaction->order_id }}</strong> telah berhasil diterbitkan.<br>
                E-Ticket Anda telah dikirimkan ke email
                <strong class="text-neutral-800 break-all">{{ $transaction->customer_email }}</strong>.
            </p>
        @else
            {{-- Pesan standar untuk transaksi Midtrans --}}
            <h2 class="text-2xl font-extrabold text-neutral-900 mb-3">Terima Kasih!</h2>
            <p class="text-neutral-500 text-xs font-semibold leading-relaxed mb-8">
                Pembayaran untuk pesanan <strong class="text-neutral-800">{{ $transaction->order_id }}</strong> sedang diproses atau telah berhasil. E-Ticket akan dikirim ke email Anda (<strong class="text-neutral-800 break-all">{{ $transaction->customer_email }}</strong>) setelah status pembayaran terkonfirmasi lunas.
            </p>
        @endif

        <a href="{{ route('home') }}" class="inline-flex px-6 h-11 items-center bg-violet-600 text-white rounded-xl font-bold text-xs hover:bg-violet-750 transition duration-150 shadow-sm">
            Kembali ke Beranda
        </a>
    </div>
</main>
@endsection
