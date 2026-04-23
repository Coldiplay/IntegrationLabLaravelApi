<?php

namespace Database\Factories;

use App\Models\Cargo;
use App\Models\CargoType;
use App\Models\Shipping;
use App\Models\ShippingOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cargo>
 */
class CargoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $shippingOrderIds = ShippingOrder::pluck('id')->toArray();
        $shippingIds = Shipping::pluck('id')->toArray();
        $cargoTypes = CargoType::pluck('id')->toArray();
        $shippingIds[] = null;

        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->text(),
            'weight' => $this->faker->randomFloat(),
            'danger_level' => $this->faker->randomElement(['Low', 'Medium', 'High', 'Extreme', null]),
            'cargo_type_id' => $this->faker->randomElement($cargoTypes),
            'shipping_id' => $this->faker->randomElement($shippingIds),
            'shipping_order_id' => $this->faker->randomElement($shippingOrderIds),
        ];
    }
}
