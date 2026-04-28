<?php

namespace App\Http\Resources;

use App\Models\ChatMember;
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
        return [
            'id' => $this->id,
            'members' => $this->whenLoaded('members', function () {
                return new ChatMembersCollection($this->collection->map(function ($member) {
                   return [
                       'id' => $member->id,
                       'name' => $member->name,
                   ];
                }));
            })
        ];
    }
}
