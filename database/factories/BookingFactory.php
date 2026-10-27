<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Field;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startHour = fake()->numberBetween(7, 20);
        $durationHours = fake()->numberBetween(1, 3);
        $startTime = sprintf('%02d:00:00', $startHour);
        $endTime = sprintf('%02d:00:00', $startHour + $durationHours);
        $pricePerHour = fake()->randomElement([80000, 100000, 120000, 150000]);

        return [
            'user_id' => User::factory(),
            'field_id' => Field::factory(),
            'schedule_id' => null,
            'booking_date' => fake()->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration_hours' => $durationHours,
            'total_price' => $pricePerHour * $durationHours,
            'status' => fake()->randomElement(['pending', 'confirmed', 'cancelled', 'completed']),
            'payment_status' => fake()->randomElement(['unpaid', 'paid', 'refunded']),
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }

    /**
     * Indicate that the booking is confirmed and paid.
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
            'payment_status' => 'paid',
        ]);
    }

    /**
     * Indicate that the booking is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);
    }

    /**
     * Indicate that the booking is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }
}
