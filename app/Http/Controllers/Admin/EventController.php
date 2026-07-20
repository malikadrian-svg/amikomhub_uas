<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $user           = Auth::user();
        $search         = $request->input('search');
        $filter         = $request->input('filter');
        $statusFilter   = $request->input('status_filter');
        $categoryFilter = $request->input('category_filter');

        $categories = \App\Models\Category::orderBy('name')->get();

        $query = Event::with(['category', 'organizer']);

        // Isolasi data: organizer hanya melihat event miliknya sendiri
        if ($user->isOrganizer()) {
            $query->where('organizer_id', $user->id);
        }

        // Filter pencarian judul
        if ($search) {
            $query->where('title', 'LIKE', '%' . $search . '%');
        }

        // Filter status (superadmin only)
        if ($user->isSuperadmin() && $statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        // Filter kategori
        if ($categoryFilter && $categoryFilter !== 'all') {
            $query->where('category_id', $categoryFilter);
        }

        // Sorting
        switch ($filter) {
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $events = $query->paginate(4);

        return view('admin.events.index', compact('events', 'search', 'filter', 'statusFilter', 'categoryFilter', 'categories'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('admin.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
            'location'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|numeric|min:1',
            'poster'      => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('poster')) {
            $file     = $request->file('poster');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/posters'), $filename);
            $data['poster_path'] = 'uploads/posters/' . $filename;
        }

        $user = Auth::user();

        // Set organizer_id ke user yang sedang login
        $data['organizer_id'] = $user->id;

        // Organizer: status pending (menunggu approval superadmin)
        // Superadmin: status approved langsung
        $data['status'] = $user->isSuperadmin() ? 'approved' : 'pending';

        Event::create($data);

        $message = $user->isOrganizer()
            ? 'Event berhasil diajukan! Menunggu persetujuan Superadmin sebelum tampil di publik.'
            : 'Data Event berhasil ditambahkan.';

        return redirect()->route('admin.events.index')->with('success', $message);
    }

    public function show(Event $event)
    {
        $this->authorizeEventAccess($event);
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $this->authorizeEventAccess($event);
        $categories = \App\Models\Category::all();
        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorizeEventAccess($event);

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
            'location'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|numeric|min:1',
            'poster'      => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('poster')) {
            // Hapus file lama jika ada
            if ($event->poster_path && file_exists(public_path($event->poster_path))) {
                unlink(public_path($event->poster_path));
            }
            $file     = $request->file('poster');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/posters'), $filename);
            $data['poster_path'] = 'uploads/posters/' . $filename;
        }

        // Jika organizer mengedit, reset status ke pending untuk re-approval
        if (Auth::user()->isOrganizer()) {
            $data['status'] = 'pending';
        }

        $event->update($data);

        $message = Auth::user()->isOrganizer()
            ? 'Event diperbarui dan diajukan ulang untuk persetujuan Superadmin.'
            : 'Event berhasil diperbarui.';

        return redirect()->route('admin.events.index')->with('success', $message);
    }

    public function destroy(Event $event)
    {
        $this->authorizeEventAccess($event);
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Data event berhasil dihapus.');
    }

    /**
     * Pastikan organizer hanya bisa mengakses event miliknya sendiri.
     */
    private function authorizeEventAccess(Event $event): void
    {
        $user = Auth::user();
        if ($user->isOrganizer() && $event->organizer_id !== $user->id) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses event ini.');
        }
    }
}
