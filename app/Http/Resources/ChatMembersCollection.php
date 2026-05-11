<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ChatMembersCollection extends ResourceCollection
{
    public $resource = User::class;
    public $collects = ChatMemberResource::class;
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->toArray();
    }
}
