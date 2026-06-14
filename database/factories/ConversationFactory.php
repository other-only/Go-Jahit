<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConversationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type' => 'general',
            'customer_id' => User::factory(),
            'penjahit_id' => User::factory(),
        ];
    }

    public function order(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'order',
        ]);
    }
}
