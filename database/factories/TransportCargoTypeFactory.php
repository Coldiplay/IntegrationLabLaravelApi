<?php

namespace Database\Factories;

use App\Models\CargoType;
use App\Models\TransportCargoType;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TransportCargoType>
 */
class TransportCargoTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cargoTypeIds = [];
        $vehicleId = null;
        foreach (Vehicle::pluck('id')->toArray() as $vehicleIdd) {
            $cargoTypeIds = array_diff(CargoType::pluck('id')->toArray(), TransportCargoType::where('vehicle_id', $vehicleIdd)->pluck('cargo_type_id')->toArray());
            if (!empty($cargoTypeIds)) {
                break;
            }
        }

        return [
            'vehicle_id' => $vehicleId,
            'cargo_type_id' => $this->faker->randomElement($cargoTypeIds),
        ];
    }
}
