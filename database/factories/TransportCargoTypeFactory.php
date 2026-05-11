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
        $cargoTypes = CargoType::all()->pluck('id')->toArray();
        $vehicles = Vehicle::all();
        $vehicleId = null;
        $cargoTypeId = null;
        foreach ($vehicles as $vehicle) {
            $blockedIds = $vehicle->supportedCargoTypes()->pluck('id')->toArray();
            foreach ($cargoTypes as $cargoType) {
                if (!in_array($cargoType, $blockedIds)) {
                    $vehicleId = $vehicle->id;
                    $cargoTypeId = $cargoType;
                    break;
                }
            }

            //$availableTypes = array_diff($cargoTypes, $vehicle->supportedCargoTypes()->pluck('id')->toArray());
            //if (!empty($availableTypes)) {
            //
            //}
        }

        if (empty($vehicleId)) {
            $vehicleId = Vehicle::factory(1)->create()->first()->id;
            $cargoTypeId = CargoType::factory(1)->create()->first()->id;
        }

        return [
            'vehicle_id' => $vehicleId,
            'cargo_type_id' => $cargoTypeId
        ];
    }
}
