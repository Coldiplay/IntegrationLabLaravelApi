<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class IncidentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($incident) {
            return [
                'id' => $incident->id,
                'status' => $incident->status,
                'incident_date' => $incident->incident_date,

                'relationships' => [
                    'driver' => [
                        'user_id' => $incident->driver->id,
                        'user' =>
                        [
                            'id' => $incident->driver->id,
                            'first_name' => $incident->driver->first_name,
                            'last_name' => $incident->driver->last_name,
                            'patronymic' => $incident->driver->patronymic,
                        ],
                    ],
                    'shipping' => [
                        'id' => $incident->shipping_id, //TODO: Надо ещё что-то или пойдёт?
                    ]
                ]
            ];
        })->toArray();
    }
}
