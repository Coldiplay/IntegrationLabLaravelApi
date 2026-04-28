<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ChatCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($chat) {
            return [
                'id' => $chat->id,
                'name' => $chat->name,
                'isPrivateChat' => $chat->isPrivateChat,

                'relationships' =>
                [
                    'members' => new ChatMembersCollection($chat->members),
                ],
            ];
        });
    }
}
