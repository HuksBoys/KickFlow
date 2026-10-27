<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Field;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * Display a paginated list of the authenticated user's bookings.
     */
    public function index(Request $request): View
    {
        $bookings = Booking::with('field')
            ->where('user_id', auth()->id())
            ->orderByDesc('booking_date')
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form to create a new booking.
     *
     * Accepts optional query parameters: field_id, date, start_time.
     */
    public function create(Request $request): View
    {
        $field = null;

        if ($request->filled('field_id')) {
            $field = Field::where('is_active', true)->findOrFail($request->integer('field_id'));
        }

        $fields = Field::where('is_active', true)->get();

        return view('bookings.create', [
            'field' => $field,
            'fields' => $fields,
            'date' => $request->input('date'),
            'startTime' => $request->input('start_time'),
        ]);
    }

    /**
     * Store a newly created booking in the database.
     *
     * Validates for double-booking prevention before saving.
     */
    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $field = Field::findOrFail($validated['field_id']);

        $startTime = $validated['start_time'];
        $durationHours = (int) $validated['duration_hours'];
        $endTime = date('H:i', strtotime($startTime) + ($durationHours * 3600));

        // Check for double-booking: any booking with overlapping time on the same field and date
        $conflict = Booking::where('field_id', $field->id)
            ->whereDate('booking_date', $validated['booking_date'])
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors(['start_time' => 'Lapangan sudah dipesan pada waktu tersebut.'])->withInput();
        }

        $totalPrice = $field->price_per_hour * $durationHours;

        Booking::create([
            'user_id' => auth()->id(),
            'field_id' => $field->id,
            'booking_date' => $validated['booking_date'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration_hours' => $durationHours,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking berhasil dibuat! Silakan lanjutkan pembayaran.');
    }

    /**
     * Display the detail of a specific booking.
     */
    public function show(Booking $booking): View
    {
        $this->authorize('view', $booking);

        $booking->load('field', 'user');

        return view('bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified booking.
     */
    public function edit(Booking $booking): View
    {
        $this->authorize('update', $booking);

        $fields = Field::where('is_active', true)->get();

        return view('bookings.edit', compact('booking', 'fields'));
    }

    /**
     * Update the specified booking in the database.
     */
    public function update(StoreBookingRequest $request, Booking $booking): RedirectResponse
    {
        $this->authorize('update', $booking);

        $validated = $request->validated();

        $field = Field::findOrFail($validated['field_id']);

        $startTime = $validated['start_time'];
        $durationHours = (int) $validated['duration_hours'];
        $endTime = date('H:i', strtotime($startTime) + ($durationHours * 3600));
        $totalPrice = $field->price_per_hour * $durationHours;

        $booking->update([
            'field_id' => $field->id,
            'booking_date' => $validated['booking_date'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration_hours' => $durationHours,
            'total_price' => $totalPrice,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking berhasil diperbarui.');
    }

    /**
     * Cancel the specified booking by setting its status to 'cancelled'.
     */
    public function cancel(Booking $booking): RedirectResponse
    {
        return $this->destroy($booking);
    }

    /**
     * Cancel the specified booking by setting its status to 'cancelled'.
     */
    public function destroy(Booking $booking): RedirectResponse
    {
        $this->authorize('delete', $booking);

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking berhasil dibatalkan.');
    }
}
