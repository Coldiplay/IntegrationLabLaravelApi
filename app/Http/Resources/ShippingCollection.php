<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ShippingCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($shipping) {
            return [
                'id' => $shipping->id,
                'delivery_point' => $shipping->delivery_point,
                $shipping->mergeWhen(isset($shipping->delivery_date), [

                    'delivery_date' => $shipping->delivery_date,
                    'shipped_date' => $shipping->shipped_date,
                ]),
                $shipping->mergeWhen(!isset($shipping->delivery_date), [
                    'estimated_delivery_date' => $shipping->when(!isset($shipping->delivery_date), $shipping->estimated_delivery_date),
                    'shipping_date' => $shipping->shipping_date,
                ]),
                'shipping_status' => $shipping->shipping_status,


                'relationships' =>
                [
                    'designated_driver' => $shipping->when(isset($shipping->designatedDriver), function () use ($shipping)
                    {
                        $driver = $shipping->designatedDriver;
                        return [
                            'user_id' => $driver->user_id,
                            'rights' => $driver->rights,
                            'user' => [
                                'id' => $driver->user_id,
                                'first_name' => $driver->user->first_name,
                                'last_name' => $driver->user->last_name,
                                'patronymic' => $driver->user->patronymic,
                                'phone' => $driver->user->phone,
                            ]
                        ];
                    }),

                    'vehicle' =>
                    [
                        "id" => $shipping->vehicle_id,
                        "vehicle_number_plate" => $shipping->vehicle->vehicle_number_plate,
                        "brand" => $shipping->vehicle->brand,
                        "model" => $shipping->vehicle->model,
                        "needed_rights" => $shipping->vehicle->needed_rights,
                        "body_type" => $shipping->vehicle->body_type,
                    ]
                ]

            ];
        })->toArray();
    }
}
