@extends('layouts.app')

@section('title', 'Selesaikan Pembayaran - ' . $transaction->event->title)

@section('content')
<main class="max-w-3xl mx-auto px-6 py-20 text-center">
    <div class="bg-white rounded-2xl border border-neutral-200 p-12 shadow-sm inline-block w-full max-w-sm">
        
        {{-- Custom Secure Shield Icon --}}
        <div class="w-16 h-16 bg-violet-50 text-violet-600 border border-violet-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        </div>

        <h2 class="text-2xl font-extrabold text-neutral-900 mb-2">Selesaikan Pembayaran</h2>
        <p class="text-neutral-500 text-xs font-semibold leading-relaxed mb-6">
            Mohon selesaikan pembayaran tiket Anda untuk event <strong class="text-neutral-805 font-bold">{{ $transaction->event->title }}</strong>.
        </p>

        <div class="p-6 bg-neutral-50 rounded-2xl border border-neutral-200 mb-6">
            <p class="text-[10px] text-neutral-400 font-extrabold uppercase tracking-wide mb-1">Total Tagihan</p>
            <h3 class="text-3xl font-extrabold text-violet-600">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</h3>
            <p class="text-[10px] text-neutral-450 mt-2 font-bold">Order ID: {{ $transaction->order_id }}</p>
        </div>

        <button id="pay-button" class="w-full h-12 bg-violet-600 text-white rounded-xl font-bold text-sm hover:bg-violet-750 transition duration-150 mb-4 shadow-sm">
            Bayar Sekarang (Snap)
        </button>

        <a href="{{ route('home') }}" class="block text-xs font-bold text-neutral-450 hover:text-neutral-605 transition">
            Kembali ke Beranda
        </a>
    </div>
</main>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function () {
        // SnapToken acquired from previous step
        snap.pay('{{ $transaction->snap_token }}', {
            // Optional
            onSuccess: function(result){
                window.location.href = "{{ route('checkout.success', $transaction->order_id) }}";
            },
            // Optional
            onPending: function(result){
                window.location.href = "{{ route('checkout.success', $transaction->order_id) }}";
            },
            // Optional
            onError: function(result){
                alert("Pembayaran Gagal!");
            }
        });
    };

    // Auto trigger
    window.onload = function() {
        document.getElementById('pay-button').click();
    }
</script>
@endsection
