<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

/**
 * ApprovalController
 * Hanya Superadmin yang dapat mengakses. Diproteksi middleware 'superadmin'.
 * Mengelola antrian event pending dari organizer.
 */
class ApprovalController extends Controller
{
    /**
     * Tampilkan daftar event dengan status pending.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status', 'pending');

        $query = Event::with(['organizer', 'category'])
            ->where('status', $status)
            ->latest();

        if ($search) {
            $query->where('title', 'LIKE', '%' . $search . '%');
        }

        $events = $query->paginate(4);

        // Juga tampilkan statistik
        $stats = [
            'pending'  => Event::pending()->count(),
            'approved' => Event::approved()->count(),
            'rejected' => Event::where('status', 'rejected')->count(),
        ];

        return view('admin.approvals.index', compact('events', 'stats', 'search', 'status'));
    }

    /**
     * Approve event: ubah status menjadi 'approved'.
     * Event akan langsung tampil di homepage publik.
     */
    public function approve(Event $event)
    {
        $event->update(['status' => 'approved']);

        return redirect()->back()->with('success',
            "Event \"<strong>{$event->title}</strong>\" berhasil diapprove dan sekarang tampil di homepage."
        );
    }

    /**
     * Reject event: ubah status menjadi 'rejected'.
     * Organizer perlu merevisi dan mengajukan ulang.
     */
    public function reject(Request $request, Event $event)
    {
        $event->update(['status' => 'rejected']);

        return redirect()->back()->with('error',
            "Event \"<strong>{$event->title}</strong>\" ditolak."
        );
    }
}
