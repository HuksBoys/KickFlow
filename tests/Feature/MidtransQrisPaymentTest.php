<?php

namespace Tests\Feature;

use App\Models\Addon;
use App\Models\Booking;
use App\Models\Field;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MidtransQrisPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_create_qris_booking_and_receive_payment_qr(): void
    {
        config()->set('services.midtrans.server_key', 'SB-Mid-server-test');
        config()->set('services.midtrans.base_url', 'https://api.sandbox.midtrans.com');
        Http::fake([
            'https://api.sandbox.midtrans.com/v2/charge' => function (HttpRequest $request): array {
                $this->assertSame('qris', $request->data()['payment_type']);
                $this->assertSame(220000, $request->data()['transaction_details']['gross_amount']);
                $this->assertSame([
                    ['id' => 'booking-1', 'price' => 200000, 'quantity' => 1, 'name' => 'Booking Arena Test'],
                    ['id' => 'addon-1', 'price' => 10000, 'quantity' => 2, 'name' => 'Rompi Tim'],
                ], $request->data()['item_details']);

                return [
                    'status_code' => '201',
                    'transaction_id' => 'midtrans-transaction-id',
                    'actions' => [[
                        'name' => 'generate-qr-code',
                        'url' => 'https://api.sandbox.midtrans.com/v2/qris/midtrans-transaction-id/qr-code',
                    ]],
                ];
            },
        ]);
        $customer = User::factory()->create();
        $field = Field::factory()->create(['name' => 'Arena Test', 'price_per_hour' => 100000, 'is_active' => true]);
        $vest = Addon::factory()->create(['name' => 'Rompi Tim', 'price' => 10000]);

        $response = $this->actingAs($customer)->post('/bookings', [
            'field_id' => $field->id,
            'booking_date' => now()->addDay()->toDateString(),
            'start_time' => '19:00',
            'duration_hours' => 2,
            'payment_method' => 'qris',
            'addons' => [$vest->id => 2],
        ]);

        $booking = Booking::firstOrFail();

        $response->assertRedirect(route('bookings.show', $booking));
        $this->assertSame('qris', $booking->payment_method);
        $this->assertSame('220000.00', $booking->total_price);
        $this->assertSame('unpaid', $booking->payment_status);
        $this->assertSame('midtrans-transaction-id', $booking->midtrans_transaction_id);
        $this->assertSame('https://api.sandbox.midtrans.com/v2/qris/midtrans-transaction-id/qr-code', $booking->qris_qr_url);
    }

    public function test_cash_booking_stays_pending_without_midtrans_charge(): void
    {
        $customer = User::factory()->create();
        $field = Field::factory()->create(['is_active' => true]);
        Http::fake();

        $this->actingAs($customer)->post('/bookings', [
            'field_id' => $field->id,
            'booking_date' => now()->addDay()->toDateString(),
            'start_time' => '19:00',
            'duration_hours' => 1,
            'payment_method' => 'cash',
        ]);

        $this->assertDatabaseHas('bookings', [
            'payment_method' => 'cash',
            'payment_status' => 'unpaid',
            'status' => 'pending',
        ]);
        Http::assertNothingSent();
    }

    public function test_verified_midtrans_settlement_auto_confirms_qris_booking(): void
    {
        config()->set('services.midtrans.server_key', 'SB-Mid-server-test');
        $booking = Booking::factory()->create([
            'payment_method' => 'qris',
            'payment_status' => 'unpaid',
            'status' => 'pending',
            'midtrans_order_id' => 'KF-QRIS-00001',
            'total_price' => 200000,
        ]);
        $payload = [
            'order_id' => $booking->midtrans_order_id,
            'status_code' => '200',
            'gross_amount' => '200000.00',
            'transaction_status' => 'settlement',
            'transaction_id' => 'midtrans-settlement-id',
        ];
        $payload['signature_key'] = hash('sha512', implode('', [
            $payload['order_id'],
            $payload['status_code'],
            $payload['gross_amount'],
            config('services.midtrans.server_key'),
        ]));

        $this->postJson(route('payments.midtrans.notification'), $payload)
            ->assertOk();

        $booking->refresh();
        $this->assertSame('confirmed', $booking->status);
        $this->assertSame('paid', $booking->payment_status);
        $this->assertSame('midtrans-settlement-id', $booking->midtrans_transaction_id);
    }

    public function test_midtrans_notification_with_invalid_signature_cannot_confirm_booking(): void
    {
        $booking = Booking::factory()->create([
            'payment_method' => 'qris',
            'payment_status' => 'unpaid',
            'status' => 'pending',
            'midtrans_order_id' => 'KF-QRIS-00002',
        ]);

        $this->postJson(route('payments.midtrans.notification'), [
            'order_id' => $booking->midtrans_order_id,
            'status_code' => '200',
            'gross_amount' => number_format((float) $booking->total_price, 2, '.', ''),
            'transaction_status' => 'settlement',
            'signature_key' => 'invalid',
        ])->assertForbidden();

        $this->assertSame('pending', $booking->fresh()->status);
        $this->assertSame('unpaid', $booking->fresh()->payment_status);
    }

    public function test_cancelled_qris_booking_cannot_be_confirmed_by_late_settlement(): void
    {
        config()->set('services.midtrans.server_key', 'SB-Mid-server-test');
        $booking = Booking::factory()->create([
            'payment_method' => 'qris',
            'payment_status' => 'unpaid',
            'status' => 'cancelled',
            'midtrans_order_id' => 'KF-QRIS-00003',
            'total_price' => 200000,
        ]);
        $payload = [
            'order_id' => $booking->midtrans_order_id,
            'status_code' => '200',
            'gross_amount' => '200000.00',
            'transaction_status' => 'settlement',
        ];
        $payload['signature_key'] = hash('sha512', implode('', [
            $payload['order_id'],
            $payload['status_code'],
            $payload['gross_amount'],
            config('services.midtrans.server_key'),
        ]));

        $this->postJson(route('payments.midtrans.notification'), $payload)->assertOk();

        $this->assertSame('cancelled', $booking->fresh()->status);
        $this->assertSame('unpaid', $booking->fresh()->payment_status);
    }
}
