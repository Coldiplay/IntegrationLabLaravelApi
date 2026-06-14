<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'content' => $this->content,
            'created_at' => $this->created_at,

            'relationships' =>[
                'sender' => [
                    'id' => $this->sender->id,
                    'login' => $this->sender->login,
                ],
                'chat' =>[
                    'id' => $this->chat_id,
                    'name' => $this->chat->name,
                    'is_private_chat' => $this->chat->is_private_chat,
                ]
            ]
        ];
    }
}
