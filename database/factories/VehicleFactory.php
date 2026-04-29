<?php

namespace Database\Factories;

use App\Enums\BodyType;
use App\Enums\Rights;
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
        $vehicle_size_length = $this->faker->randomFloat(2, 200, 1000);
        $vehicle_size_width = $this->faker->randomFloat(2, 100, 200);
        $vehicle_size_height = $this->faker->randomFloat(2, 180, 300);


        return [
            'vehicle_number_plate' => fake()->text(15),
            'brand' => $this->faker->word(),
            'model' => $this->faker->word(),
            'needed_rights' => $this->faker->randomElement(Rights::getValues()),
            'lifting_capacity' => $this->faker->randomFloat(2, 1000, 5000),
            'body_type' => $this->faker->randomElement(BodyType::getKeys()),

            'vehicle_size_length' => $vehicle_size_length,
            'vehicle_size_width' => $vehicle_size_width,
            'vehicle_size_height' => $vehicle_size_height,

            'body_size_length' => $this->faker->randomFloat(2, 190, $vehicle_size_height - 10),
            'body_size_width' => $this->faker->randomFloat(2, 80, $vehicle_size_width - 20),
            'body_size_height' => $this->faker->randomFloat(2, 150, $vehicle_size_height - 30),

            'max_cargo_volume' => $this->faker->randomFloat(2, 30, 100),
            'vehicle_weight' => $this->faker->randomFloat(2, 1000, 3000),
            'number_of_axes' => $this->faker->randomDigitNotZero()
        ];
    }
}
