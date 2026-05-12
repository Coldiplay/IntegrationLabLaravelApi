<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncidentResource extends JsonResource
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
            'status' => $this->status,
            'incident_date' => $this->incident_date,

            'relationships' => [
                'driver' => [
                    'id' => $this->driver->id,
                    'first_name' => $this->driver->first_name,
                    'last_name' => $this->driver->last_name,
                    'patronymic' => $this->driver->patronymic,
                    'rights' => $this->driver->rights, //TODO: Поменять userResource?
                ],
                'shipping' => $this->shipping//new ShippingResource($this->shipping)
            ]
        ];
    }
}
