<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\MidtransQrisService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use RuntimeException;

class PaymentCallbackController extends Controller
{
    public function __construct(private readonly MidtransQrisService $midtrans) {}

    public function midtransNotification(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'order_id' => ['required', 'string'],
            'status_code' => ['required', 'string'],
            'gross_amount' => ['required', 'string'],
            'transaction_status' => ['required', 'string'],
            'transaction_id' => ['nullable', 'string'],
            'signature_key' => ['required', 'string'],
        ]);

        if (! $this->midtrans->isValidSignature($payload)) {
            abort(403);
        }

        $booking = Booking::where('midtrans_order_id', $payload['order_id'])->firstOrFail();

        if ($booking->payment_method !== 'qris' || number_format((float) $booking->total_price, 2, '.', '') !== $payload['gross_amount']) {
            abort(422);
        }

        if ($payload['transaction_status'] === 'settlement' && $booking->status !== 'cancelled') {
            $booking->update([
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'midtrans_transaction_id' => $payload['transaction_id'] ?? $booking->midtrans_transaction_id,
            ]);
        }

        return response()->json(['ok' => true]);
    }

    public function qrisQr(Booking $booking): Response
    {
        Gate::authorize('view', $booking);

        if ($booking->payment_method !== 'qris' || ! $booking->qris_qr_url) {
            abort(404);
        }

        try {
            return response($this->midtrans->qrImage($booking->qris_qr_url), 200, [
                'Content-Type' => 'image/png',
                'Cache-Control' => 'private, no-store',
            ]);
        } catch (RuntimeException) {
            abort(502);
        }
    }
}
