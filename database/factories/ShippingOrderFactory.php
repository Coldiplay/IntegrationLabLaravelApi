<?php

namespace Database\Factories;

use App\Models\ShippingOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ShippingOrder>
 */
class ShippingOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_date' => $this->faker->date(),
            'receiver_fio' => $this->faker->name(),
            'receiver_phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'status' => $this->faker->randomElement(['InProcessing' , 'InProgress']),
            'shipping_date' => $this->faker->date(),
            'sent_date' => $this->faker->date(),
            'received_date' => $this->faker->date(),
        ];
    }
}
