<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isSuperadmin()) {
            return $this->superadminDashboard();
        }

        return $this->organizerDashboard($user);
    }

    /**
     * Dashboard Superadmin: statistik global + antrian approval.
     */
    private function superadminDashboard()
    {
        // Statistik global platform
        $totalRevenue = Transaction::whereIn('status', ['settlement', 'success', 'Success'])
            ->sum('total_price');

        $ticketsSold = Transaction::whereIn('status', ['settlement', 'success', 'Success'])
            ->count();

        $activeEvents = Event::approved()->where('date', '>=', now())->count();

        $pendingOrders = Transaction::where('status', 'pending')->count();

        // Antrian event pending (approval feed)
        $pendingEvents = Event::with(['organizer', 'category'])
            ->pending()
            ->latest()
            ->take(10)
            ->get();

        // Jumlah organizer aktif
        $activeOrganizers = User::where('role', 'organizer')->count();

        // Transaksi terbaru (semua organizer)
        $recentTransactions = Transaction::with('event')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'ticketsSold',
            'activeEvents',
            'pendingOrders',
            'pendingEvents',
            'activeOrganizers',
            'recentTransactions'
        ))->with('role', 'superadmin');
    }

    /**
     * Dashboard Organizer: statistik terisolasi milik organizer ini.
     */
    private function organizerDashboard(User $user)
    {
        // Ambil ID event milik organizer ini
        $myEventIds = Event::where('organizer_id', $user->id)->pluck('id');

        // Pendapatan bersih organizer ini
        $totalRevenue = Transaction::whereIn('status', ['settlement', 'success', 'Success'])
            ->whereIn('event_id', $myEventIds)
            ->sum('total_price');

        // Tiket terjual organizer ini
        $ticketsSold = Transaction::whereIn('status', ['settlement', 'success', 'Success'])
            ->whereIn('event_id', $myEventIds)
            ->count();

        // Event aktif organizer ini
        $activeEvents = Event::where('organizer_id', $user->id)
            ->where('date', '>=', now())
            ->count();

        // Transaksi pending untuk event organizer ini
        $pendingOrders = Transaction::where('status', 'pending')
            ->whereIn('event_id', $myEventIds)
            ->count();

        // Daftar event organizer dengan statistik stok
        $myEvents = Event::where('organizer_id', $user->id)
            ->with('category')
            ->latest()
            ->take(5)
            ->get();

        // Transaksi terbaru untuk event organizer ini
        $recentTransactions = Transaction::with('event')
            ->whereIn('event_id', $myEventIds)
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'ticketsSold',
            'activeEvents',
            'pendingOrders',
            'myEvents',
            'recentTransactions'
        ))->with('role', 'organizer');
    }

    /**
     * API untuk mengambil data statistik berdasarkan rentang waktu.
     */
    public function getStatsData(Request $request)
    {
        $user = Auth::user();
        $range = $request->query('range', '1_year'); // Pilihan: 7_days, 30_days, 1_year

        // Tentukan batas tanggal awal
        $startDate = now();
        $isDaily = false;
        
        if ($range === '7_days') {
            $startDate = now()->subDays(6)->startOfDay();
            $isDaily = true;
        } elseif ($range === '30_days') {
            $startDate = now()->subDays(29)->startOfDay();
            $isDaily = true;
        } else {
            // Default 1_year
            $startDate = now()->subMonths(11)->startOfMonth();
            $isDaily = false;
        }

        if ($user->isSuperadmin()) {
            return $this->getSuperadminStatsData($startDate, $isDaily, $range);
        }

        return $this->getOrganizerStatsData($user, $startDate, $isDaily, $range);
    }

    /**
     * Mengambil statistik untuk Superadmin.
     */
    private function getSuperadminStatsData($startDate, $isDaily, $range)
    {
        $driver = \Illuminate\Support\Facades\DB::getDriverName();
        $dateExpr = $isDaily 
            ? ($driver === 'sqlite' ? "strftime('%Y-%m-%d', created_at)" : "DATE(created_at)")
            : ($driver === 'sqlite' ? "strftime('%Y-%m', created_at)" : "DATE_FORMAT(created_at, '%Y-%m')");

        // Statistik kartu
        $totalRevenue = Transaction::whereIn('status', ['settlement', 'success', 'Success'])
            ->where('created_at', '>=', $startDate)
            ->sum('total_price');

        $ticketsSold = Transaction::whereIn('status', ['settlement', 'success', 'Success'])
            ->where('created_at', '>=', $startDate)
            ->count();

        $activeEvents = Event::approved()
            ->where('created_at', '>=', $startDate)
            ->count();

        $activeOrganizers = User::where('role', 'organizer')
            ->where('created_at', '>=', $startDate)
            ->count();

        // Inisialisasi peta data tren
        $labels = [];
        $dataUsers = [];
        $dataEvents = [];
        
        $iterations = $isDaily ? ($range === '7_days' ? 7 : 30) : 12;
        for ($i = $iterations - 1; $i >= 0; $i--) {
            if ($isDaily) {
                $date = now()->subDays($i);
                $label = $date->format('d M');
                $key = $date->format('Y-m-d');
            } else {
                $date = now()->subMonths($i);
                $label = $date->format('M Y');
                $key = $date->format('Y-m');
            }
            $labels[] = $label;
            $dataUsers[$key] = 0;
            $dataEvents[$key] = 0;
        }

        // Tren pertumbuhan pengguna
        $users = User::where('role', 'user')
            ->where('created_at', '>=', $startDate)
            ->selectRaw("{$dateExpr} as date_key, COUNT(id) as count")
            ->groupBy('date_key')
            ->get();
        foreach ($users as $u) {
            $k = $u->date_key;
            if (isset($dataUsers[$k])) {
                $dataUsers[$k] = (int)$u->count;
            }
        }

        // Tren pembuatan event
        $events = Event::where('created_at', '>=', $startDate)
            ->selectRaw("{$dateExpr} as date_key, COUNT(id) as count")
            ->groupBy('date_key')
            ->get();
        foreach ($events as $e) {
            $k = $e->date_key;
            if (isset($dataEvents[$k])) {
                $dataEvents[$k] = (int)$e->count;
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'cards' => [
                    'total_revenue' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'),
                    'tickets_sold' => number_format($ticketsSold, 0, ',', '.'),
                    'active_events' => number_format($activeEvents, 0, ',', '.'),
                    'active_organizers' => number_format($activeOrganizers, 0, ',', '.'),
                ],
                'charts' => [
                    'user_growth' => [
                        'labels' => $labels,
                        'datasets' => array_values($dataUsers)
                    ],
                    'event_growth' => [
                        'labels' => $labels,
                        'datasets' => array_values($dataEvents)
                    ]
                ]
            ]
        ]);
    }

    /**
     * Mengambil statistik untuk Organizer.
     */
    private function getOrganizerStatsData(User $user, $startDate, $isDaily, $range)
    {
        $driver = \Illuminate\Support\Facades\DB::getDriverName();
        $dateExpr = $isDaily 
            ? ($driver === 'sqlite' ? "strftime('%Y-%m-%d', created_at)" : "DATE(created_at)")
            : ($driver === 'sqlite' ? "strftime('%Y-%m', created_at)" : "DATE_FORMAT(created_at, '%Y-%m')");

        $myEventIds = Event::where('organizer_id', $user->id)->pluck('id');

        // Statistik kartu
        $totalRevenue = Transaction::whereIn('status', ['settlement', 'success', 'Success'])
            ->whereIn('event_id', $myEventIds)
            ->where('created_at', '>=', $startDate)
            ->sum('total_price');

        $ticketsSold = Transaction::whereIn('status', ['settlement', 'success', 'Success'])
            ->whereIn('event_id', $myEventIds)
            ->where('created_at', '>=', $startDate)
            ->count();

        $activeEvents = Event::where('organizer_id', $user->id)
            ->where('created_at', '>=', $startDate)
            ->count();

        $pendingOrders = Transaction::where('status', 'pending')
            ->whereIn('event_id', $myEventIds)
            ->where('created_at', '>=', $startDate)
            ->count();

        // Inisialisasi peta data tren pendapatan (Line Chart)
        $labels = [];
        $dataRevenue = [];
        
        $iterations = $isDaily ? ($range === '7_days' ? 7 : 30) : 12;
        for ($i = $iterations - 1; $i >= 0; $i--) {
            if ($isDaily) {
                $date = now()->subDays($i);
                $label = $date->format('d M');
                $key = $date->format('Y-m-d');
            } else {
                $date = now()->subMonths($i);
                $label = $date->format('M Y');
                $key = $date->format('Y-m');
            }
            $labels[] = $label;
            $dataRevenue[$key] = 0;
        }

        // Tren pendapatan
        $revenueData = Transaction::whereIn('status', ['settlement', 'success', 'Success'])
            ->whereIn('event_id', $myEventIds)
            ->where('created_at', '>=', $startDate)
            ->selectRaw("{$dateExpr} as date_key, SUM(total_price) as sum")
            ->groupBy('date_key')
            ->get();
        foreach ($revenueData as $r) {
            $k = $r->date_key;
            if (isset($dataRevenue[$k])) {
                $dataRevenue[$k] = (int)$r->sum;
            }
        }

        // Penjualan tiket per event (Bar Chart)
        $ticketSales = Transaction::whereIn('transactions.status', ['settlement', 'success', 'Success'])
            ->whereIn('transactions.event_id', $myEventIds)
            ->where('transactions.created_at', '>=', $startDate)
            ->join('events', 'transactions.event_id', '=', 'events.id')
            ->selectRaw('events.title as event_title, COUNT(transactions.id) as count')
            ->groupBy('event_title')
            ->get();

        $barLabels = [];
        $barDatasets = [];
        foreach ($ticketSales as $t) {
            $barLabels[] = strlen($t->event_title) > 20 ? substr($t->event_title, 0, 17) . '...' : $t->event_title;
            $barDatasets[] = (int)$t->count;
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'cards' => [
                    'total_revenue' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'),
                    'tickets_sold' => number_format($ticketsSold, 0, ',', '.'),
                    'active_events' => number_format($activeEvents, 0, ',', '.'),
                    'pending_orders' => number_format($pendingOrders, 0, ',', '.'),
                ],
                'charts' => [
                    'revenue_growth' => [
                        'labels' => $labels,
                        'datasets' => array_values($dataRevenue)
                    ],
                    'ticket_sales_per_event' => [
                        'labels' => $barLabels,
                        'datasets' => $barDatasets
                    ]
                ]
            ]
        ]);
    }
}
