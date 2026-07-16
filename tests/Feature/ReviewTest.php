<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $event;
    private $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name' => 'Seminar IT',
            'slug' => 'seminar-it'
        ]);

        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        // Event in the past (already ended > H+1)
        $this->event = Event::create([
            'category_id' => $this->category->id,
            'title' => 'Past Event',
            'description' => 'Past Event Description',
            'date' => Carbon::now()->subDays(2),
            'location' => 'Online',
            'price' => 0,
            'stock' => 10
        ]);
    }

    /** @test */
    public function guests_cannot_post_reviews()
    {
        $response = $this->postJson(route('reviews.store', $this->event->id), [
            'rating' => 5,
            'comment' => 'Great event!'
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function users_who_did_not_purchase_cannot_review()
    {
        $response = $this->actingAs($this->user)->postJson(route('reviews.store', $this->event->id), [
            'rating' => 5,
            'comment' => 'Great event!'
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function users_who_purchased_successfully_can_review_after_h_plus_1()
    {
        // Purchase ticket successfully
        Transaction::create([
            'event_id' => $this->event->id,
            'order_id' => 'TRX-112233',
            'customer_name' => $this->user->name,
            'customer_email' => $this->user->email,
            'customer_phone' => '0812345678',
            'total_price' => 0,
            'status' => 'Success'
        ]);

        $response = $this->actingAs($this->user)->postJson(route('reviews.store', $this->event->id), [
            'rating' => 5,
            'comment' => 'Loved it!'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('reviews', [
            'user_id' => $this->user->id,
            'event_id' => $this->event->id,
            'rating' => 5,
            'comment' => 'Loved it!'
        ]);
    }

    /** @test */
    public function users_cannot_review_before_h_plus_1()
    {
        // Create an event that is in the future
        $futureEvent = Event::create([
            'category_id' => $this->category->id,
            'title' => 'Future Event',
            'description' => 'Future Event Description',
            'date' => Carbon::now()->addDay(),
            'location' => 'Online',
            'price' => 0,
            'stock' => 10
        ]);

        Transaction::create([
            'event_id' => $futureEvent->id,
            'order_id' => 'TRX-445566',
            'customer_name' => $this->user->name,
            'customer_email' => $this->user->email,
            'customer_phone' => '0812345678',
            'total_price' => 0,
            'status' => 'Success'
        ]);

        $response = $this->actingAs($this->user)->postJson(route('reviews.store', $futureEvent->id), [
            'rating' => 5,
            'comment' => 'Amazing!'
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function user_can_only_review_once_per_event()
    {
        Transaction::create([
            'event_id' => $this->event->id,
            'order_id' => 'TRX-112233',
            'customer_name' => $this->user->name,
            'customer_email' => $this->user->email,
            'customer_phone' => '0812345678',
            'total_price' => 0,
            'status' => 'Success'
        ]);

        // First review
        $this->actingAs($this->user)->postJson(route('reviews.store', $this->event->id), [
            'rating' => 5,
            'comment' => 'Loved it!'
        ])->assertStatus(200);

        // Second review
        $response = $this->actingAs($this->user)->postJson(route('reviews.store', $this->event->id), [
            'rating' => 4,
            'comment' => 'Another review attempt'
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function review_rating_must_be_between_1_and_5()
    {
        Transaction::create([
            'event_id' => $this->event->id,
            'order_id' => 'TRX-112233',
            'customer_name' => $this->user->name,
            'customer_email' => $this->user->email,
            'customer_phone' => '0812345678',
            'total_price' => 0,
            'status' => 'Success'
        ]);

        // Rating = 6 (Too high)
        $response = $this->actingAs($this->user)->postJson(route('reviews.store', $this->event->id), [
            'rating' => 6,
            'comment' => 'Amazing!'
        ]);
        $response->assertStatus(422); // Validation fail on JSON web requests returns 422

        // Rating = 0 (Too low)
        $response = $this->actingAs($this->user)->postJson(route('reviews.store', $this->event->id), [
            'rating' => 0,
            'comment' => 'Amazing!'
        ]);
        $response->assertStatus(422);
    }
}
