<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * OrganizerController
 * Hanya Superadmin yang dapat mengakses. Diproteksi middleware 'superadmin'.
 * Mengelola daftar organizer (HIMA/Kepanitiaan) yang terdaftar.
 */
class OrganizerController extends Controller
{
    /**
     * Tampilkan semua organizer yang terdaftar dengan statistik ringkas.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = User::where('role', 'organizer');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        }

        $organizers = $query->latest()->paginate(4);

        // Enrichment: tambahkan statistik per organizer
        $organizers->getCollection()->transform(function (User $organizer) {
            $eventIds = Event::where('organizer_id', $organizer->id)->pluck('id');

            $organizer->stats = [
                'total_events'    => Event::where('organizer_id', $organizer->id)->count(),
                'approved_events' => Event::where('organizer_id', $organizer->id)->approved()->count(),
                'pending_events'  => Event::where('organizer_id', $organizer->id)->pending()->count(),
                'total_revenue'   => Transaction::whereIn('event_id', $eventIds)
                    ->whereIn('status', ['settlement', 'success', 'Success'])
                    ->sum('total_price'),
                'tickets_sold'    => Transaction::whereIn('event_id', $eventIds)
                    ->whereIn('status', ['settlement', 'success', 'Success'])
                    ->count(),
            ];

            return $organizer;
        });

        $totalOrganizers = User::where('role', 'organizer')->count();

        return view('admin.organizers.index', compact('organizers', 'totalOrganizers', 'search'));
    }

    /**
     * Ubah role user biasa menjadi organizer (superadmin buat akun organizer).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        $data['role']     = 'organizer';
        $data['password'] = bcrypt($data['password']);

        User::create($data);

        return redirect()->route('admin.organizers.index')
            ->with('success', "Akun organizer \"{$data['name']}\" berhasil dibuat.");
    }

    /**
     * Promote user biasa ke organizer.
     */
    public function promote(User $user)
    {
        $user->update(['role' => 'organizer']);
        return redirect()->back()->with('success', "{$user->name} berhasil dipromosikan menjadi Organizer.");
    }

    /**
     * Demote organizer kembali ke user biasa.
     */
    public function demote(User $user)
    {
        if ($user->isSuperadmin()) {
            return redirect()->back()->with('error', 'Superadmin tidak dapat di-demote.');
        }
        $user->update(['role' => 'user']);
        return redirect()->back()->with('success', "{$user->name} berhasil diubah kembali menjadi User.");
    }
}
