<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingResource extends JsonResource
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
            'delivery_point' => $this->delivery_point,
            'shipping_date' => $this->shipping_date,
            'estimated_delivery_date' => $this->estimated_delivery_date,

            'delivery_date' => $this->delivery_date,
            'shipped_date' => $this->shipped_date,
            'shipping_status' => $this->shipping_status,

            'relationships' =>
                [
                    'driver' => $this->when(isset($this->designatedDriver), function ()
                    {
                        $driver = $this->designatedDriver;
                        return [
                            'id' => $driver->user_id,
                            'first_name' => $driver->user->first_name,
                            'last_name' => $driver->user->last_name,
                            'patronymic' => $driver->user->patronymic,
                            'phone' => $driver->user->phone,
                            'rights' => $driver->rights,
                        ];
                    }),

                    'vehicle' => new VehicleResource($this->vehicle),
                ]

        ];
    }
}
