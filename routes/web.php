<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\Admin\OrganizerController;

// ─── Rute Autentikasi User ──────────────────────────────────────────────────
Route::get('/login', [UserAuthController::class, 'showLogin'])->name('user.login');
Route::post('/login', [UserAuthController::class, 'login'])->name('user.login.post');
Route::post('/logout', [UserAuthController::class, 'logout'])->name('user.logout');
Route::get('/register', [UserAuthController::class, 'showRegister'])->name('user.register');
Route::post('/register', [UserAuthController::class, 'register'])->name('user.register.post');

// ─── Rute SSO Google (Socialite) ────────────────────────────────────────────
Route::get('/auth/redirect/{provider}', [SocialAuthController::class, 'redirectToProvider'])->name('social.redirect');
Route::get('/auth/callback/{provider}', [SocialAuthController::class, 'handleProviderCallback'])->name('social.callback');

// ─── Rute User Area (Perlu Login) ───────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/reviews', [\App\Http\Controllers\ReviewController::class, 'showEventReviews'])->name('events.reviews');
    Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::get('/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');
    Route::post('/reviews/{event}', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
});

// Rute Webhook Midtrans (tanpa auth)
Route::post('/midtrans/callback', [\App\Http\Controllers\MidtransWebhookController::class, 'handle']);


// ─── Rute Admin Panel ───────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    // Rute Login/Logout (bebas akses)
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // ── Rute Panel Bersama (Superadmin + Organizer) ─────────────────────────
    // Diproteksi: harus login + memiliki akses panel (superadmin/organizer)
    Route::middleware(['auth', 'panel'])->group(function () {

        // Dashboard (tampil berbeda per role)
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('dashboard/api/stats-data', [DashboardController::class, 'getStatsData'])->name('dashboard.api');

        // Kelola Event (dengan isolasi data per role di controller)
        Route::resource('events', AdminEventController::class);

        // Transaksi (organizer hanya melihat transaksi eventnya)
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    });

    // ── Rute Eksklusif Superadmin ────────────────────────────────────────────
    // Diproteksi: harus login + role superadmin
    Route::middleware(['auth', 'superadmin'])->group(function () {

        // Antrian Approval Event
        Route::get('approvals', [ApprovalController::class, 'index'])->name('approvals.index');
        Route::post('approvals/{event}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
        Route::post('approvals/{event}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');

        // Kelola Organizer
        Route::get('organizers', [OrganizerController::class, 'index'])->name('organizers.index');
        Route::post('organizers', [OrganizerController::class, 'store'])->name('organizers.store');
        Route::post('organizers/{user}/promote', [OrganizerController::class, 'promote'])->name('organizers.promote');
        Route::post('organizers/{user}/demote', [OrganizerController::class, 'demote'])->name('organizers.demote');

        // Kategori & Partner
        Route::resource('categories', CategoryController::class);
        Route::resource('partners', PartnerController::class);
    });
});
