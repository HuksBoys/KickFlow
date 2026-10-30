<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CashierController extends Controller
{
    /**
     * Display the cashier panel with all bookings that need attention.
     */
    public function index(Request $request): View
    {
        $query = Booking::with(['user', 'field']);

        // Filter by status
        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment') && $request->payment !== 'semua') {
            $query->where('payment_status', $request->payment);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }

        // Search by booking ID or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $bookings = $query->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        // Stats for the cashier dashboard
        $pendingCount = Booking::where('status', 'pending')->count();
        $confirmedTodayCount = Booking::where('status', 'confirmed')
            ->whereDate('booking_date', today())
            ->count();
        $unpaidCount = Booking::where('payment_status', 'unpaid')
            ->where('status', '!=', 'cancelled')
            ->count();
        $todayRevenue = Booking::whereDate('booking_date', today())
            ->whereIn('status', ['confirmed', 'completed'])
            ->where('payment_status', 'paid')
            ->sum('total_price');

        return view('admin.cashier.index', compact(
            'bookings',
            'pendingCount',
            'confirmedTodayCount',
            'unpaidCount',
            'todayRevenue'
        ));
    }

    /**
     * Display the detail of a specific booking for cashier review.
     */
    public function show(Booking $booking): View
    {
        $booking->load(['user', 'field']);

        return view('admin.cashier.show', compact('booking'));
    }

    /**
     * Confirm a pending booking (change status to confirmed).
     */
    public function confirm(Booking $booking): RedirectResponse
    {
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Hanya booking dengan status pending yang dapat dikonfirmasi.');
        }

        $booking->update(['status' => 'confirmed']);

        return back()->with('success', 'Booking #KF-'.str_pad($booking->id, 5, '0', STR_PAD_LEFT).' berhasil dikonfirmasi!');
    }

    /**
     * Mark a booking's payment as paid.
     */
    public function markPaid(Booking $booking): RedirectResponse
    {
        if ($booking->payment_status === 'paid') {
            return back()->with('error', 'Pembayaran sudah tercatat lunas.');
        }

        if ($booking->status === 'cancelled') {
            return back()->with('error', 'Tidak dapat memproses pembayaran untuk booking yang dibatalkan.');
        }

        $booking->update([
            'payment_status' => 'paid',
            'status' => 'confirmed',
        ]);

        return back()->with('success', 'Pembayaran booking #KF-'.str_pad($booking->id, 5, '0', STR_PAD_LEFT).' berhasil dikonfirmasi!');
    }

    /**
     * Mark a confirmed booking as completed.
     */
    public function complete(Booking $booking): RedirectResponse
    {
        if (! in_array($booking->status, ['confirmed'], true)) {
            return back()->with('error', 'Hanya booking terkonfirmasi yang dapat diselesaikan.');
        }

        $booking->update(['status' => 'completed']);

        return back()->with('success', 'Booking #KF-'.str_pad($booking->id, 5, '0', STR_PAD_LEFT).' telah selesai!');
    }

    /**
     * Cancel a booking from the cashier panel.
     */
    public function cancel(Booking $booking): RedirectResponse
    {
        if ($booking->status === 'cancelled') {
            return back()->with('error', 'Booking sudah dibatalkan sebelumnya.');
        }

        if ($booking->status === 'completed') {
            return back()->with('error', 'Booking yang sudah selesai tidak dapat dibatalkan.');
        }

        $updateData = ['status' => 'cancelled'];

        if ($booking->payment_status === 'paid') {
            $updateData['payment_status'] = 'refunded';
        }

        $booking->update($updateData);

        return back()->with('success', 'Booking #KF-'.str_pad($booking->id, 5, '0', STR_PAD_LEFT).' berhasil dibatalkan.');
    }
}
