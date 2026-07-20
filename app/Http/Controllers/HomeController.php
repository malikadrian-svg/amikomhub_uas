<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Partner;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $partners   = Partner::all();

        // Hanya tampilkan event yang sudah di-approve oleh Superadmin
        // dan tanggalnya belum terlampaui (sesuai PRD section 5)
        $query = Event::with('category')
            ->approved()                          // status = 'approved'
            ->where('date', '>=', now()->subDays(3))
            ->orderByRaw("CASE WHEN date >= NOW() THEN 0 ELSE 1 END")
            ->orderBy('date', 'asc');

        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $events = $query->get();
        return view('welcome', compact('events', 'categories', 'partners'));
    }
}
