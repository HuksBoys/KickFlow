<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Field;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard with stats and recent bookings.
     */
    public function index(): View
    {
        $todayBookings = Booking::whereDate('booking_date', today())->count();

        $monthlyRevenue = Booking::whereMonth('booking_date', now()->month)
            ->whereYear('booking_date', now()->year)
            ->whereIn('status', ['confirmed', 'completed'])
            ->sum('total_price');

        $activeFields = Field::where('is_active', true)->count();

        $pendingBookings = Booking::where('status', 'pending')->count();

        $recentBookings = Booking::with(['user', 'field'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'todayBookings',
            'monthlyRevenue',
            'activeFields',
            'pendingBookings',
            'recentBookings'
        ));
    }
}
