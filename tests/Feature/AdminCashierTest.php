<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Field;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCashierTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest cannot access admin cashier panel.
     */
    public function test_guest_cannot_access_cashier_panel(): void
    {
        $response = $this->get('/admin/cashier');

        $response->assertRedirect('/login');
    }

    /**
     * Test regular customer receives 403 when accessing cashier panel.
     */
    public function test_customer_cannot_access_cashier_panel(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)->get('/admin/cashier');

        $response->assertStatus(403);
    }

    /**
     * Test admin can view cashier dashboard with bookings list.
     */
    public function test_admin_can_view_cashier_dashboard(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create(['name' => 'Ahmad Futsal']);
        $field = Field::factory()->create(['name' => 'Lapangan Nusantara']);

        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'field_id' => $field->id,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'total_price' => 200000,
        ]);

        $response = $this->actingAs($admin)->get('/admin/cashier');

        $response->assertStatus(200);
        $response->assertSee('Kasir &amp; Konfirmasi Pesanan', false);
        $response->assertSee('Ahmad Futsal');
        $response->assertSee('Lapangan Nusantara');
        $response->assertSee('200.000');
    }

    /**
     * Test admin can view booking receipt / detail page in cashier.
     */
    public function test_admin_can_view_cashier_booking_detail(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->create();

        $response = $this->actingAs($admin)->get("/admin/cashier/{$booking->id}");

        $response->assertStatus(200);
        $code = '#KF-'.str_pad($booking->id, 5, '0', STR_PAD_LEFT);
        $response->assertSee($code);
        $response->assertSee('Struk Resmi Pemesanan Lapangan');
    }

    /**
     * Test admin can confirm a pending booking.
     */
    public function test_admin_can_confirm_a_pending_booking(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($admin)->patch("/admin/cashier/{$booking->id}/confirm");

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('confirmed', $booking->fresh()->status);
    }

    /**
     * Test admin can mark an unpaid booking as paid.
     */
    public function test_admin_can_mark_booking_as_paid(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->create([
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        $response = $this->actingAs($admin)->patch("/admin/cashier/{$booking->id}/mark-paid");

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $fresh = $booking->fresh();
        $this->assertEquals('paid', $fresh->payment_status);
        $this->assertEquals('confirmed', $fresh->status);
    }

    /**
     * Test admin can mark a confirmed booking as completed.
     */
    public function test_admin_can_mark_confirmed_booking_as_completed(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->create([
            'status' => 'confirmed',
            'payment_status' => 'paid',
        ]);

        $response = $this->actingAs($admin)->patch("/admin/cashier/{$booking->id}/complete");

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('completed', $booking->fresh()->status);
    }

    /**
     * Test admin can cancel a booking from cashier.
     */
    public function test_admin_can_cancel_booking(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->create([
            'status' => 'pending',
            'payment_status' => 'paid',
        ]);

        $response = $this->actingAs($admin)->patch("/admin/cashier/{$booking->id}/cancel");

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $fresh = $booking->fresh();
        $this->assertEquals('cancelled', $fresh->status);
        $this->assertEquals('refunded', $fresh->payment_status);
    }

    /**
     * Test admin can filter bookings by status and payment status.
     */
    public function test_admin_can_filter_bookings_in_cashier(): void
    {
        $admin = User::factory()->admin()->create();
        $user1 = User::factory()->create(['name' => 'Budi Pending']);
        $user2 = User::factory()->create(['name' => 'Joko Confirmed']);

        $bookingPending = Booking::factory()->create([
            'user_id' => $user1->id,
            'status' => 'pending',
        ]);

        $bookingConfirmed = Booking::factory()->create([
            'user_id' => $user2->id,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($admin)->get('/admin/cashier?status=pending');

        $response->assertStatus(200);
        $response->assertSee('Budi Pending');
        $response->assertDontSee('Joko Confirmed');
    }
}
