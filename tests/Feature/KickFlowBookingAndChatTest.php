<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Field;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KickFlowBookingAndChatTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test field index page displays fields.
     */
    public function test_fields_index_page_displays_active_fields(): void
    {
        $field = Field::factory()->create([
            'name' => 'Lapangan Senayan Arena',
            'type' => 'indoor',
            'price_per_hour' => 150000,
            'is_active' => true,
        ]);

        $response = $this->get('/fields');

        $response->assertStatus(200);
        $response->assertSee('Lapangan Senayan Arena');
        $response->assertSee('150.000');
    }

    /**
     * Test user can create a booking successfully.
     */
    public function test_user_can_create_a_booking(): void
    {
        $user = User::factory()->create();
        $field = Field::factory()->create([
            'price_per_hour' => 100000,
            'is_active' => true,
        ]);

        $bookingDate = now()->addDays(2)->format('Y-m-d');

        $response = $this->actingAs($user)->post('/bookings', [
            'field_id' => $field->id,
            'booking_date' => $bookingDate,
            'start_time' => '10:00',
            'duration_hours' => 2,
            'notes' => 'Uji coba booking KickFlow',
        ]);

        $response->assertRedirect('/bookings');
        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'field_id' => $field->id,
            'start_time' => '10:00',
            'duration_hours' => 2,
            'total_price' => 200000,
            'status' => 'pending',
        ]);
        $booking = Booking::where('user_id', $user->id)->first();
        $this->assertNotNull($booking);
        $this->assertEquals($bookingDate, $booking->booking_date->format('Y-m-d'));
    }

    /**
     * Test double booking prevention rejects overlapping booking on same field, date, and time.
     */
    public function test_double_booking_prevention_rejects_overlapping_time(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $field = Field::factory()->create([
            'price_per_hour' => 120000,
            'is_active' => true,
        ]);

        $bookingDate = now()->addDays(3)->format('Y-m-d');

        // First user books 14:00 - 16:00
        Booking::create([
            'user_id' => $user1->id,
            'field_id' => $field->id,
            'booking_date' => $bookingDate,
            'start_time' => '14:00',
            'end_time' => '16:00',
            'duration_hours' => 2,
            'total_price' => 240000,
            'status' => 'confirmed',
            'payment_status' => 'paid',
        ]);

        // Second user tries to book the same field on the same date at 14:00
        $response = $this->actingAs($user2)->post('/bookings', [
            'field_id' => $field->id,
            'booking_date' => $bookingDate,
            'start_time' => '14:00',
            'duration_hours' => 1,
        ]);

        // Should be rejected with validation error on start_time
        $response->assertSessionHasErrors('start_time');

        // Ensure only 1 booking exists in the database
        $this->assertEquals(1, Booking::where('field_id', $field->id)->whereDate('booking_date', $bookingDate)->count());
    }

    /**
     * Test AI Chat endpoint returns valid JSON response and logs conversation.
     */
    public function test_ai_chat_api_endpoint_returns_reply_and_logs(): void
    {
        Field::factory()->create([
            'name' => 'Lapangan Bintang',
            'type' => 'indoor',
            'price_per_hour' => 100000,
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/chat', [
            'message' => 'Halo, apakah ada lapangan yang tersedia?',
            'session_id' => 'test_session_123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'reply',
            'function_called',
            'timestamp',
        ]);

        $this->assertNotEmpty($response->json('reply'));

        // Assert chat message is logged
        $this->assertDatabaseHas('chat_logs', [
            'session_id' => 'test_session_123',
            'role' => 'user',
            'message' => 'Halo, apakah ada lapangan yang tersedia?',
        ]);

        $this->assertDatabaseHas('chat_logs', [
            'session_id' => 'test_session_123',
            'role' => 'assistant',
        ]);
    }
}
