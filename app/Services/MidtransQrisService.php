<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MidtransQrisService
{
    /**
     * Create a Midtrans QRIS transaction for a booking.
     *
     * @return array{transaction_id: string, qr_url: string}
     */
    public function charge(Booking $booking): array
    {
        $serverKey = (string) config('services.midtrans.server_key');

        if ($serverKey === '') {
            throw new RuntimeException('Midtrans Server Key belum dikonfigurasi.');
        }

        $response = $this->client($serverKey)->post('/v2/charge', [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $booking->midtrans_order_id,
                'gross_amount' => (int) $booking->total_price,
            ],
            'item_details' => [[
                'id' => 'booking-'.$booking->id,
                'price' => (int) $booking->total_price,
                'quantity' => 1,
                'name' => 'Booking '.$booking->field->name,
            ]],
            'customer_details' => [
                'first_name' => $booking->user->name,
                'email' => $booking->user->email,
                'phone' => $booking->user->phone,
            ],
            'qris' => ['acquirer' => 'gopay'],
        ]);

        if ($response->failed()) {
            throw new RuntimeException('Gagal membuat QRIS Midtrans.');
        }

        $data = $response->json();
        $qrUrl = collect($data['actions'] ?? [])
            ->firstWhere('name', 'generate-qr-code')['url'] ?? null;

        if (! isset($data['transaction_id']) || ! $qrUrl) {
            throw new RuntimeException('Respons QRIS Midtrans tidak lengkap.');
        }

        return [
            'transaction_id' => $data['transaction_id'],
            'qr_url' => $qrUrl,
        ];
    }

    public function isValidSignature(array $payload): bool
    {
        $signature = $payload['signature_key'] ?? '';
        $serverKey = (string) config('services.midtrans.server_key');

        if ($signature === '' || $serverKey === '') {
            return false;
        }

        $expected = hash('sha512', implode('', [
            $payload['order_id'] ?? '',
            $payload['status_code'] ?? '',
            $payload['gross_amount'] ?? '',
            $serverKey,
        ]));

        return hash_equals($expected, $signature);
    }

    public function qrImage(string $url): string
    {
        $response = $this->client((string) config('services.midtrans.server_key'))->get($url);

        if ($response->failed()) {
            throw new RuntimeException('Gagal mengambil gambar QRIS Midtrans.');
        }

        return $response->body();
    }

    private function client(string $serverKey): PendingRequest
    {
        return Http::baseUrl((string) config('services.midtrans.base_url'))
            ->withBasicAuth($serverKey, '')
            ->acceptJson()
            ->timeout(15);
    }
}
