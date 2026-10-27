<?php

namespace Database\Factories;

use App\Models\ChatLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ChatLog>
 */
class ChatLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'session_id' => Str::uuid()->toString(),
            'role' => fake()->randomElement(['user', 'assistant']),
            'message' => fake()->paragraph(),
            'function_call_data' => null,
        ];
    }

    /**
     * Indicate that the chat log belongs to an authenticated user.
     */
    public function forUser(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => User::factory(),
        ]);
    }

    /**
     * Indicate that the chat log is from the user role.
     */
    public function fromUser(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'user',
        ]);
    }

    /**
     * Indicate that the chat log is from the assistant role.
     */
    public function fromAssistant(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'assistant',
        ]);
    }
}
