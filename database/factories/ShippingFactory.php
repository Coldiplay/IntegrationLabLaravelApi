<?php

namespace Database\Factories;

use App\Models\Driver;
use App\Models\Shipping;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Shipping>
 */
class ShippingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $vehicleIds = Vehicle::pluck('id')->toArray();
        $driverIds = Driver::pluck('id')->toArray();
        return [
            'delivery_point' => $this->faker->address(),
            'estimated_delivery_date' => $this->faker->date(),
            'delivery_date' => $this->faker->boolean() ? $this->faker->date() : null,
            'shipping_status' => $this->faker->randomElement(['InProcessing', 'ReadyToShip', 'Shipping', 'Delivered', 'Incident']),
            'shipping_date' => $this->faker->date(),
            'shipped_date' => $this->faker->boolean() ? $this->faker->date() : null,
            'vehicle_id' => $this->faker->randomElement($vehicleIds),
            'designated_driver_id' => $this->faker->randomElement($driverIds),
        ];
    }
}
