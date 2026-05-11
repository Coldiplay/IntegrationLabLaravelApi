<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_private_chat' => $this->is_private_chat,
            'relationships' =>
            [
                'messages' => $this->whenLoaded('messages', function () {
                    return new MessagesCollection($this->messages);
                }),
                'members' => $this->whenLoaded('members', function () {
                    return new ChatMembersCollection($this->chatMembers);
                })
            ]
        ];
    }
}
