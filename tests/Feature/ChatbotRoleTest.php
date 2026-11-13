<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Field;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatbotRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_request_today_operational_summary(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = User::factory()->create(['name' => 'Budi']);
        $field = Field::factory()->create(['name' => 'Arena Utama', 'is_active' => true]);
        Booking::factory()->create([
            'user_id' => $customer->id,
            'field_id' => $field->id,
            'booking_date' => today(),
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'total_price' => 150000,
        ]);

        $this->actingAs($admin)->postJson('/api/chat', [
            'message' => 'Ringkasan operasional hari ini',
            'session_id' => 'admin-operational-summary',
        ])
            ->assertOk()
            ->assertJsonPath('function_called', 'get_admin_operational_summary')
            ->assertJsonPath('reply', fn (string $reply): bool => str($reply)->contains('Ringkasan operasional hari ini'));
    }

    public function test_customer_cannot_request_admin_operational_summary(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($customer)->postJson('/api/chat', [
            'message' => 'Ringkasan operasional hari ini',
            'session_id' => 'customer-operational-summary',
        ])
            ->assertOk()
            ->assertJsonPath('function_called', null)
            ->assertJsonPath('reply', fn (string $reply): bool => str($reply)->contains('khusus admin'));
    }

    public function test_customer_can_start_booking_recommendation_flow(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($customer)->postJson('/api/chat', [
            'message' => 'Bantu pilihkan lapangan',
            'session_id' => 'customer-recommendation-flow',
        ])
            ->assertOk()
            ->assertJsonPath('function_called', 'start_booking_recommendation')
            ->assertJsonPath('reply', fn (string $reply): bool => str($reply)->lower()->contains('tanggal bermain'));
    }

    public function test_customer_receives_field_recommendations_after_answering_booking_questions(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        Field::factory()->create([
            'name' => 'Arena Hemat',
            'is_active' => true,
            'price_per_hour' => 100000,
        ]);

        $sessionId = 'customer-recommendation-complete';
        $this->actingAs($customer)->postJson('/api/chat', [
            'message' => 'Bantu pilihkan lapangan',
            'session_id' => $sessionId,
        ]);
        $this->actingAs($customer)->postJson('/api/chat', [
            'message' => 'besok',
            'session_id' => $sessionId,
        ]);
        $this->actingAs($customer)->postJson('/api/chat', [
            'message' => '19:00',
            'session_id' => $sessionId,
        ]);
        $this->actingAs($customer)->postJson('/api/chat', [
            'message' => '2 jam',
            'session_id' => $sessionId,
        ]);

        $this->actingAs($customer)->postJson('/api/chat', [
            'message' => '200000',
            'session_id' => $sessionId,
        ])
            ->assertOk()
            ->assertJsonPath('function_called', 'get_booking_recommendations')
            ->assertJsonPath('reply', fn (string $reply): bool => str($reply)->contains('Arena Hemat'));
    }

    public function test_admin_must_confirm_before_chatbot_confirms_booking(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->create(['status' => 'pending']);
        $sessionId = 'admin-confirm-booking';

        $this->actingAs($admin)->postJson('/api/chat', [
            'message' => "Konfirmasi booking #KF-{$booking->id}",
            'session_id' => $sessionId,
        ])
            ->assertOk()
            ->assertJsonPath('function_called', 'request_admin_booking_confirmation');

        $this->assertSame('pending', $booking->fresh()->status);

        $this->actingAs($admin)->postJson('/api/chat', [
            'message' => 'Setujui',
            'session_id' => $sessionId,
        ])
            ->assertOk()
            ->assertJsonPath('function_called', 'confirm_booking')
            ->assertJsonPath('reply', fn (string $reply): bool => str($reply)->contains('berhasil dikonfirmasi'));

        $this->assertSame('confirmed', $booking->fresh()->status);
    }
}
