<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
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
            'vehicle_number_plate' => $this->vehicle_number_plate,
            'brand' => $this->brand,
            'model' => $this->model,
            'needed_rights' => $this->needed_rights,
            'lifting_capacity' => $this->lifting_capacity,
            'body_type' => $this->body_type,
            'vehicle_size' => [
                'width' => $this->vehicle_size_width,
                'length' => $this->vehicle_size_length,
                'height' => $this->vehicle_size_height
            ],
            'body_size' => [
                'width' => $this->body_size_width,
                'length' => $this->body_size_length,
                'height' => $this->body_size_height
            ],
            'max_cargo_volume' => $this->max_cargo_volume,
            'vehicle_weight' => $this->vehicle_weight,
            'number_of_axes' => $this->number_of_axes,

        ];
    }
}
