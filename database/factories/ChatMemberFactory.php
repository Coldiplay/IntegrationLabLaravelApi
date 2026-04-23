<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChatMember>
 */
class ChatMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $chatIds = Chat::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();
        return [
            'chat_id' => $this->faker->randomElement($chatIds),
            'user_id' => $this->faker->randomElement($userIds),
        ];
    }
}
