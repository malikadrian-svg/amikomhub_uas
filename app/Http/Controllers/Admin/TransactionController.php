<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user   = Auth::user();
        $search = $request->input('search');
        $filter = $request->input('filter');

        $query = Transaction::with('event');

        // Isolasi data: organizer hanya melihat transaksi untuk event miliknya
        if ($user->isOrganizer()) {
            $myEventIds = Event::where('organizer_id', $user->id)->pluck('id');
            $query->whereIn('event_id', $myEventIds);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'LIKE', '%' . $search . '%')
                  ->orWhere('customer_name', 'LIKE', '%' . $search . '%')
                  ->orWhere('customer_email', 'LIKE', '%' . $search . '%')
                  ->orWhereHas('event', function ($ev) use ($search) {
                      $ev->where('title', 'LIKE', '%' . $search . '%');
                  });
            });
        }

        switch ($filter) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'price_desc':
                $query->orderBy('total_price', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('total_price', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $transactions = $query->paginate(4);

        return view('admin.transactions.index', compact('transactions', 'search', 'filter'));
    }
}
