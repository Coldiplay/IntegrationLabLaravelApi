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
        $chat = Chat::all()->random();
        //$chatIds = Chat::pluck('id')->toArray();
        $existingMembers = $chat->chatMembers()->pluck('id')->toArray();
        $userIds = array_diff(User::pluck('id')->toArray(), $existingMembers);
        if(empty($userIds))
        {
            $user = User::factory(1)->create();
            $userIds = $user->pluck('id')->toArray();
        }

        return [
            'chat_id' => $chat->id,
            'user_id' => $this->faker->randomElement($userIds),
        ];
    }
}
