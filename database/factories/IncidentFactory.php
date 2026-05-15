<?php

namespace Database\Factories;

use App\Enums\IncidentStatus;
use App\Models\Driver;
use App\Models\Incident;
use App\Models\Shipping;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Incident>
 */
class IncidentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $shippingIds = array_diff(Shipping::pluck('id')->toArray(), Incident::pluck('id')->toArray());
        $userIds = Driver::pluck('user_id')->toArray();
        return [
            'driver_id' => $this->faker->randomElement($userIds),
            'shipping_id' => $this->faker->randomElement($shippingIds),
            'description' => $this->faker->text(500),
            'incident_date' => $this->faker->dateTime(),
            'status' => $this->faker->randomElement(IncidentStatus::getKeys()),
        ];
    }
}
