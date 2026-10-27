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

    /**
     * Test AI Chat can create a booking directly via conversational command.
     */
    public function test_ai_chat_can_create_booking_directly_via_chat(): void
    {
        $user = User::factory()->create();
        $field = Field::factory()->create([
            'name' => 'Garuda Sport Futsal',
            'price_per_hour' => 150000,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->postJson('/api/chat', [
            'message' => 'Pesan lapangan Garuda Sport Futsal besok jam 19:00 selama 2 jam',
            'session_id' => 'test_session_booking_1',
        ]);

        $response->assertStatus(200);
        $this->assertEquals('create_booking', $response->json('function_called'));
        $this->assertStringContainsString('Pemesanan lapangan futsal berhasil dibuat', $response->json('reply'));

        $tomorrow = now()->addDay()->format('Y-m-d');
        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'field_id' => $field->id,
            'start_time' => '19:00',
            'end_time' => '21:00',
            'duration_hours' => 2,
            'total_price' => 300000,
            'status' => 'pending',
        ]);
        $booking = Booking::where('user_id', $user->id)->first();
        $this->assertNotNull($booking);
        $this->assertEquals($tomorrow, $booking->booking_date->format('Y-m-d'));
    }

    /**
     * Test AI Chat can cancel a booking directly via conversational command.
     */
    public function test_ai_chat_can_cancel_booking_directly_via_chat(): void
    {
        $user = User::factory()->create();
        $field = Field::factory()->create([
            'name' => 'Arena Merdeka',
            'is_active' => true,
        ]);

        $booking = Booking::create([
            'user_id' => $user->id,
            'field_id' => $field->id,
            'booking_date' => now()->addDays(2)->format('Y-m-d'),
            'start_time' => '17:00',
            'end_time' => '18:00',
            'duration_hours' => 1,
            'total_price' => 100000,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        $response = $this->actingAs($user)->postJson('/api/chat', [
            'message' => "Tolong batalkan booking #KF-{$booking->id}",
            'session_id' => 'test_session_cancel_1',
        ]);

        $response->assertStatus(200);
        $this->assertEquals('cancel_booking', $response->json('function_called'));
        $this->assertStringContainsString('berhasil dibatalkan', $response->json('reply'));

        $this->assertEquals('cancelled', $booking->fresh()->status);
    }
}
