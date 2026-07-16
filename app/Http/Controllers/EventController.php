<?php

namespace App\Http\Controllers;

class EventController extends Controller
{
    public function show(\App\Models\Event $event)
    {
        // Mengambil daftar kategori untuk keperluan menu footer
        $categories = \App\Models\Category::all();

        // Me-render view dengan membawa data kategori dan data spesifik acara tersebut
        return view('event-detail', compact('categories', 'event'));
    }

    public function checkout()
    {
        return view('checkout');
    }

    public function ticket()
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $transactions = \App\Models\Transaction::with(['event.reviews'])
            ->where('customer_email', auth()->user()->email)
            ->whereIn('status', ['Success', 'success', 'settlement'])
            ->latest()
            ->get();

        $reviewedEventIds = \App\Models\Review::where('user_id', auth()->id())
            ->pluck('event_id')
            ->toArray();

        $categories = \App\Models\Category::all();

        return view('ticket', compact('transactions', 'reviewedEventIds', 'categories'));
    }

    public function indexAdmin()
    {
        return view('admin.events');
    }
}
