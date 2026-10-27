<?php

namespace Database\Factories;

use App\Models\Field;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startHour = fake()->numberBetween(7, 21);
        $startTime = sprintf('%02d:00:00', $startHour);
        $endTime = sprintf('%02d:00:00', $startHour + 1);

        return [
            'field_id' => Field::factory(),
            'date' => fake()->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'is_available' => true,
        ];
    }

    /**
     * Indicate that the schedule slot is unavailable.
     */
    public function unavailable(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_available' => false,
        ]);
    }
}
