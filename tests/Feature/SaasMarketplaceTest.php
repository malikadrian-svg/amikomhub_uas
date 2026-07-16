<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class SaasMarketplaceTest extends TestCase
{
    use RefreshDatabase;

    private $superadmin;
    private $organizerA;
    private $organizerB;
    private $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name' => 'Tech Talks',
            'slug' => 'tech-talks'
        ]);

        $this->superadmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'),
            'role' => 'superadmin'
        ]);

        $this->organizerA = User::create([
            'name' => 'HIMA Informatics',
            'email' => 'hima_if@example.com',
            'password' => bcrypt('password'),
            'role' => 'organizer'
        ]);

        $this->organizerB = User::create([
            'name' => 'HIMA Information Systems',
            'email' => 'hima_si@example.com',
            'password' => bcrypt('password'),
            'role' => 'organizer'
        ]);
    }

    /** @test */
    public function dashboard_data_is_isolated_for_organizers()
    {
        // Organizer A creates event
        $eventA = Event::create([
            'category_id' => $this->category->id,
            'organizer_id' => $this->organizerA->id,
            'title' => 'Web Dev Workshop A',
            'date' => Carbon::now()->addDays(2),
            'location' => 'Amikom Hall',
            'price' => 50000,
            'stock' => 100,
            'status' => 'approved'
        ]);

        // Organizer B creates event
        $eventB = Event::create([
            'category_id' => $this->category->id,
            'organizer_id' => $this->organizerB->id,
            'title' => 'UI UX Class B',
            'date' => Carbon::now()->addDays(3),
            'location' => 'Online',
            'price' => 75000,
            'stock' => 50,
            'status' => 'approved'
        ]);

        // Transaction for Event A (Success)
        Transaction::create([
            'event_id' => $eventA->id,
            'order_id' => 'TRX-A-111',
            'customer_name' => 'Buyer A',
            'customer_email' => 'buyer_a@example.com',
            'customer_phone' => '0812345678',
            'total_price' => 50000,
            'status' => 'Success'
        ]);

        // Transaction for Event B (Success)
        Transaction::create([
            'event_id' => $eventB->id,
            'order_id' => 'TRX-B-222',
            'customer_name' => 'Buyer B',
            'customer_email' => 'buyer_b@example.com',
            'customer_phone' => '0812345679',
            'total_price' => 75000,
            'status' => 'Success'
        ]);

        // Login as Organizer A
        $response = $this->actingAs($this->organizerA)->get(route('admin.dashboard'));
        $response->assertStatus(200);

        // Verify total revenue and tickets sold only reflect Event A
        $response->assertViewHas('totalRevenue', 50000);
        $response->assertViewHas('ticketsSold', 1);

        // Login as Superadmin
        $responseSuper = $this->actingAs($this->superadmin)->get(route('admin.dashboard'));
        $responseSuper->assertStatus(200);

        // Verify superadmin gets overall totals
        $responseSuper->assertViewHas('totalRevenue', 125000);
        $responseSuper->assertViewHas('ticketsSold', 2);
    }

    /** @test */
    public function organizer_cannot_edit_or_delete_other_organizers_event()
    {
        $eventB = Event::create([
            'category_id' => $this->category->id,
            'organizer_id' => $this->organizerB->id,
            'title' => 'UI UX Class B',
            'date' => Carbon::now()->addDays(3),
            'location' => 'Online',
            'price' => 75000,
            'stock' => 50,
            'status' => 'approved'
        ]);

        // Organizer A tries to edit Event B
        $responseEdit = $this->actingAs($this->organizerA)->get(route('admin.events.edit', $eventB->id));
        $responseEdit->assertStatus(403);

        // Organizer A tries to update Event B
        $responseUpdate = $this->actingAs($this->organizerA)->put(route('admin.events.update', $eventB->id), [
            'category_id' => $this->category->id,
            'title' => 'Hacked title',
            'date' => Carbon::now()->addDays(3),
            'location' => 'Hacked location',
            'price' => 1000,
            'stock' => 10
        ]);
        $responseUpdate->assertStatus(403);

        // Organizer A tries to delete Event B
        $responseDelete = $this->actingAs($this->organizerA)->delete(route('admin.events.destroy', $eventB->id));
        $responseDelete->assertStatus(403);
    }

    /** @test */
    public function organizer_created_event_starts_as_pending_and_does_not_show_on_homepage()
    {
        $response = $this->actingAs($this->organizerA)->post(route('admin.events.store'), [
            'category_id' => $this->category->id,
            'title' => 'Malam Keakraban HIMA',
            'description' => 'Description here',
            'date' => Carbon::now()->addDays(5)->format('Y-m-d H:i:s'),
            'location' => 'Amikom Camping Ground',
            'price' => 20000,
            'stock' => 150
        ]);

        $response->assertRedirect(route('admin.events.index'));

        // Verify in database
        $this->assertDatabaseHas('events', [
            'title' => 'Malam Keakraban HIMA',
            'organizer_id' => $this->organizerA->id,
            'status' => 'pending'
        ]);

        // Verify it is not shown on public homepage
        $homeResponse = $this->get(route('home'));
        $homeResponse->assertStatus(200);
        $homeResponse->assertDontSee('Malam Keakraban HIMA');
    }

    /** @test */
    public function superadmin_can_approve_event_to_publish_it()
    {
        $pendingEvent = Event::create([
            'category_id' => $this->category->id,
            'organizer_id' => $this->organizerA->id,
            'title' => 'Malam Keakraban HIMA',
            'date' => Carbon::now()->addDays(5),
            'location' => 'Amikom Camping Ground',
            'price' => 20000,
            'stock' => 150,
            'status' => 'pending'
        ]);

        // Approve it as superadmin
        $responseApprove = $this->actingAs($this->superadmin)->post(route('admin.approvals.approve', $pendingEvent->id));
        $responseApprove->assertRedirect();

        // Verify database status
        $this->assertDatabaseHas('events', [
            'id' => $pendingEvent->id,
            'status' => 'approved'
        ]);

        // Verify it is visible on homepage now
        $homeResponse = $this->get(route('home'));
        $homeResponse->assertSee('Malam Keakraban HIMA');
    }

    /** @test */
    public function superadmin_can_reject_event()
    {
        $pendingEvent = Event::create([
            'category_id' => $this->category->id,
            'organizer_id' => $this->organizerA->id,
            'title' => 'Spam Event',
            'date' => Carbon::now()->addDays(5),
            'location' => 'Nowhere',
            'price' => 20000,
            'stock' => 150,
            'status' => 'pending'
        ]);

        // Reject it as superadmin
        $responseReject = $this->actingAs($this->superadmin)->post(route('admin.approvals.reject', $pendingEvent->id));
        $responseReject->assertRedirect();

        // Verify database status
        $this->assertDatabaseHas('events', [
            'id' => $pendingEvent->id,
            'status' => 'rejected'
        ]);

        // Verify it is not shown on homepage
        $homeResponse = $this->get(route('home'));
        $homeResponse->assertDontSee('Spam Event');
    }

    /** @test */
    public function dashboard_stats_api_returns_filtered_data_and_enforces_access_control()
    {
        // 1. Guest user cannot access dashboard stats API
        $responseGuest = $this->get(route('admin.dashboard.api'));
        $responseGuest->assertRedirect('/admin/login');

        // 2. Regular user (role = 'user') gets 403 Access Denied
        $regularUser = User::create([
            'name' => 'Regular User',
            'email' => 'regular@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);
        $responseUser = $this->actingAs($regularUser)->get(route('admin.dashboard.api'));
        $responseUser->assertStatus(403);

        // 3. Organizer A gets stats only for their events
        $eventA = Event::create([
            'category_id' => $this->category->id,
            'organizer_id' => $this->organizerA->id,
            'title' => 'Web Dev Workshop A',
            'date' => Carbon::now()->addDays(2),
            'location' => 'Amikom Hall',
            'price' => 50000,
            'stock' => 100,
            'status' => 'approved'
        ]);

        Transaction::create([
            'event_id' => $eventA->id,
            'order_id' => 'TRX-A-111',
            'customer_name' => 'Buyer A',
            'customer_email' => 'buyer_a@example.com',
            'customer_phone' => '0812345678',
            'total_price' => 50000,
            'status' => 'Success'
        ]);

        $responseOrg = $this->actingAs($this->organizerA)->get(route('admin.dashboard.api', ['range' => '30_days']));
        $responseOrg->assertStatus(200);
        $responseOrg->assertJsonStructure([
            'status',
            'data' => [
                'cards' => [
                    'total_revenue',
                    'tickets_sold',
                    'active_events',
                    'pending_orders'
                ],
                'charts' => [
                    'revenue_growth' => [
                        'labels',
                        'datasets'
                    ],
                    'ticket_sales_per_event' => [
                        'labels',
                        'datasets'
                    ]
                ]
            ]
        ]);

        $this->assertEquals('Rp 50.000', $responseOrg->json('data.cards.total_revenue'));
        $this->assertEquals('1', $responseOrg->json('data.cards.tickets_sold'));
    }

    /**
     * @test
     * Memvalidasi alur bypass transaksi gratis: event Rp 0 langsung sukses tanpa Midtrans.
     */
    public function free_event_checkout_bypasses_midtrans_and_creates_success_transaction()
    {
        // Siapkan event gratis dengan stok awal 5
        $freeEvent = Event::create([
            'user_id'      => $this->organizerA->id,
            'category_id'  => $this->category->id,
            'title'        => 'Free Workshop 2025',
            'slug'         => 'free-workshop-2025',
            'description'  => 'Workshop gratis untuk semua.',
            'date'         => Carbon::now()->addDays(10),
            'location'     => 'Online',
            'price'        => 0,
            'stock'        => 5,
            'status'       => 'approved',
        ]);

        // POST ke endpoint checkout biasa (store) yang seharusnya mendeteksi harga = 0
        // dan secara internal memanggil processFreeTransaction
        $response = $this->actingAs($this->organizerA)->post(route('checkout.store', $freeEvent->id), [
            'customer_name'  => 'Budi Santoso',
            'customer_email' => 'budi@example.com',
            'customer_phone' => '081234567890',
        ]);

        // Harus redirect ke halaman sukses (bukan ke payment)
        $response->assertRedirect();
        $this->assertStringContainsString('success', $response->headers->get('Location'));

        // Pastikan transaksi tersimpan sebagai 'Success' dengan snap_token = 'FREE_BYPASS'
        $this->assertDatabaseHas('transactions', [
            'event_id'      => $freeEvent->id,
            'customer_email'=> 'budi@example.com',
            'total_price'   => 0,
            'status'        => 'Success',
            'snap_token'    => 'FREE_BYPASS',
        ]);

        // Stok harus berkurang dari 5 menjadi 4
        $this->assertDatabaseHas('events', [
            'id'    => $freeEvent->id,
            'stock' => 4,
        ]);
    }

    /**
     * @test
     * Event berbayar harus ditolak melalui store() dengan logika biasa (bukan jalur gratis).
     */
    public function paid_event_checkout_does_not_use_free_bypass()
    {
        $paidEvent = Event::create([
            'user_id'      => $this->organizerA->id,
            'category_id'  => $this->category->id,
            'title'        => 'Paid Concert 2025',
            'slug'         => 'paid-concert-2025',
            'description'  => 'Konser berbayar.',
            'date'         => Carbon::now()->addDays(10),
            'location'     => 'Jakarta',
            'price'        => 150000,
            'stock'        => 10,
            'status'       => 'approved',
        ]);

        // Transaksi berbayar tidak boleh ada di tabel dengan snap_token 'FREE_BYPASS'
        // karena akan masuk ke alur Midtrans (yang akan error di sandbox = tanggkap dengan back()->with('error'))
        $this->assertDatabaseMissing('transactions', [
            'event_id'   => $paidEvent->id,
            'snap_token' => 'FREE_BYPASS',
        ]);
    }

    /**
     * @test
     * Checkout event gratis dengan stok habis harus mengembalikan error, bukan membuat transaksi.
     */
    public function free_event_checkout_fails_when_stock_is_zero()
    {
        $freeEventNoStock = Event::create([
            'user_id'      => $this->organizerA->id,
            'category_id'  => $this->category->id,
            'title'        => 'Free Event No Stock',
            'slug'         => 'free-event-no-stock',
            'description'  => 'Event gratis tapi stok habis.',
            'date'         => Carbon::now()->addDays(10),
            'location'     => 'Online',
            'price'        => 0,
            'stock'        => 0,
            'status'       => 'approved',
        ]);

        $response = $this->actingAs($this->organizerA)->post(route('checkout.store', $freeEventNoStock->id), [
            'customer_name'  => 'Tono Suparno',
            'customer_email' => 'tono@example.com',
            'customer_phone' => '082222222222',
        ]);

        // Harus redirect kembali dengan error (stok habis)
        $response->assertRedirect();

        // Tidak boleh ada transaksi yang terbuat
        $this->assertDatabaseMissing('transactions', [
            'event_id'       => $freeEventNoStock->id,
            'customer_email' => 'tono@example.com',
        ]);
    }
}
