<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->user_id,
            'rights' => $this->rights,
            'drivers_license' => $this->drivers_license,

            'relationships' =>
            [
                'user' => new UserResource($this->user)
            ]
        ];
    }
}
