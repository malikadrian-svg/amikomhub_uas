<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Review;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request, $eventId)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Anda harus login terlebih dahulu.'], 401);
        }

        $event = Event::findOrFail($eventId);

        // 1. Kelayakan Pengulas (Eligible Buyers Only)
        $hasPurchased = Transaction::where('customer_email', Auth::user()->email)
            ->where('event_id', $eventId)
            ->where('status', 'Success')
            ->exists();

        if (!$hasPurchased) {
            return response()->json(['message' => 'Ulasan hanya dapat dikirimkan oleh pembeli tiket yang sah.'], 403);
        }

        // 2. Satu pengguna hanya dapat memberikan maksimal satu (1) ulasan per event.
        $alreadyReviewed = Review::where('user_id', Auth::id())
            ->where('event_id', $eventId)
            ->exists();

        if ($alreadyReviewed) {
            return response()->json(['message' => 'Anda sudah memberikan ulasan untuk event ini.'], 422);
        }

        // 3. Jeda Waktu Penilaian (Post-Event Rating Delay)
        // Diaktifkan sehari setelah acara berakhir (event.date + 24 jam)
        $reviewAllowedAfter = Carbon::parse($event->date)->addDay();
        if (now()->lt($reviewAllowedAfter)) {
            return response()->json(['message' => 'Ulasan baru dapat diberikan 1 hari setelah acara selesai.'], 422);
        }

        // 4. Validasi input
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        // 5. Simpan review
        Review::create([
            'user_id' => Auth::id(),
            'event_id' => $eventId,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json(['message' => 'Terima kasih! Ulasan Anda berhasil dikirim.'], 200);
    }

    /**
     * Display public reviews feed for an event.
     */
    public function showEventReviews($eventId)
    {
        $event = Event::with(['reviews.user', 'category'])->findOrFail($eventId);
        $reviews = $event->reviews()->with('user')->latest()->paginate(10);
        $categories = \App\Models\Category::all();

        return view('reviews.event-reviews', compact('event', 'reviews', 'categories'));
    }
}
