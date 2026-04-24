<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = User::pluck('id')->toArray();
        $chats = Chat::all();
        $chatId = -1;
        foreach ($userIds as $userId) {
            foreach ($chats as $chat) {
                if ($chat->chatMembers()->where('user_id', $userId)->exists()) {
                    $chatId = $chat->id;
                    goto foundUserId;
                }
            }
        }

        foundUserId:
        return [
            'content' => $this->faker->realText(300),
            'date' => new DateTime('now'),
            'sender_id' => $this->faker->randomElement($userIds),
            'chat_id' => $chatId,
        ];
    }
}
