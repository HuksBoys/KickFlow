<?php

namespace Tests\Feature;

use App\Models\Addon;
use App\Models\Booking;
use App\Models\Field;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAddonManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_booking_addons(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post('/admin/addons', [
            'name' => 'Rompi Tim',
            'price' => 15000,
            'is_active' => '1',
        ])->assertRedirect('/admin/addons');

        $addon = Addon::firstOrFail();

        $this->actingAs($admin)->put("/admin/addons/{$addon->id}", [
            'name' => 'Air Mineral Botol',
            'price' => 5000,
        ])->assertRedirect('/admin/addons');

        $this->assertDatabaseHas('addons', [
            'id' => $addon->id,
            'name' => 'Air Mineral Botol',
            'price' => 5000,
            'is_active' => false,
        ]);
    }

    public function test_customer_cannot_manage_booking_addons(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($customer)->get('/admin/addons')->assertForbidden();
    }

    public function test_customer_booking_includes_selected_active_addons_in_total(): void
    {
        $customer = User::factory()->create();
        $field = Field::factory()->create(['is_active' => true, 'price_per_hour' => 100000]);
        $vest = Addon::factory()->create(['name' => 'Rompi Tim', 'price' => 15000]);
        $water = Addon::factory()->create(['name' => 'Air Mineral', 'price' => 5000]);
        $inactive = Addon::factory()->inactive()->create(['name' => 'Bola Cadangan', 'price' => 10000]);

        $this->actingAs($customer)->post('/bookings', [
            'field_id' => $field->id,
            'booking_date' => now()->addDay()->toDateString(),
            'start_time' => '19:00',
            'duration_hours' => 2,
            'payment_method' => 'cash',
            'addons' => [
                $vest->id => 2,
                $water->id => 4,
            ],
        ])->assertRedirect(route('bookings.show', 1));

        $booking = Booking::firstOrFail();

        $this->assertSame('250000.00', $booking->total_price);
        $this->assertDatabaseHas('addon_booking', [
            'booking_id' => $booking->id,
            'addon_id' => $vest->id,
            'quantity' => 2,
            'unit_price' => 15000,
        ]);
        $this->assertDatabaseHas('addon_booking', [
            'booking_id' => $booking->id,
            'addon_id' => $water->id,
            'quantity' => 4,
            'unit_price' => 5000,
        ]);
        $this->actingAs($customer)->get(route('bookings.show', $booking))
            ->assertOk()
            ->assertSee('Rompi Tim')
            ->assertSee('Air Mineral');

        $this->actingAs($customer)->post('/bookings', [
            'field_id' => $field->id,
            'booking_date' => now()->addDays(2)->toDateString(),
            'start_time' => '19:00',
            'duration_hours' => 1,
            'payment_method' => 'cash',
            'addons' => [$inactive->id => 1],
        ])->assertSessionHasErrors('addons');
    }
}
