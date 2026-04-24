<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vehicle_number_plate' => fake()->text(15),
            'brand' => $this->faker->word(),
            'model' => $this->faker->word(),
            'needed_rights' => $this->faker->randomElement(['A', 'B']),
            'lifting_capacity' => $this->faker->randomFloat(2, 1000, 5000),
            'body_type' => $this->faker->randomElement(['S', 'M']),
            'max_cargo_volume' => $this->faker->randomFloat(2, 30, 100),
            'vehicle_weight' => $this->faker->randomFloat(2, 1000, 3000),
            'number_of_axes' => $this->faker->randomDigitNotZero()
        ];
    }
}
