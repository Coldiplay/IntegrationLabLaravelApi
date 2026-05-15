<?php

namespace Database\Factories;

use App\Models\Chat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Chat>
 */
class ChatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isPrivate = $this->faker->boolean();
        return [
            'name' => $isPrivate ? null : $this->faker->word(),
            'is_private_chat' => $isPrivate,
        ];
    }
}
