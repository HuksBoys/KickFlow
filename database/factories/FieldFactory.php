<?php

namespace Database\Factories;

use App\Models\Field;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Field>
 */
class FieldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fieldNames = [
            'Lapangan Garuda Sport',
            'Futsal Arena Merdeka',
            'GOR Nusantara',
            'Sport Center Bintang',
            'Lapangan Merah Putih',
            'Futsal Hall Sejahtera',
            'Arena Sport Mandiri',
            'Lapangan Bola Mas',
        ];

        return [
            'name' => fake()->unique()->randomElement($fieldNames),
            'type' => fake()->randomElement(['indoor', 'outdoor']),
            'price_per_hour' => fake()->randomElement([80000, 100000, 120000, 150000, 175000]),
            'description' => fake()->paragraph(2),
            'image_path' => null,
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the field is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the field is indoor.
     */
    public function indoor(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'indoor',
        ]);
    }

    /**
     * Indicate that the field is outdoor.
     */
    public function outdoor(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'outdoor',
        ]);
    }
}
