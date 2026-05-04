<?php

namespace Database\Factories;

use App\Models\Driver;
use App\Models\DriversShift;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DriversShift>
 */
class DriversShiftFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-1 month',
            date_add(now()->toDateTime(),
                date_interval_create_from_date_string("-2 days")));
        $end = date_create($start->format('Y-m-d H:i:s'));
        $driversIds = Driver::pluck('user_id')->toArray();
        return [
            'start' => $start,
            'end' => $this->faker->boolean()
                //?  $this->faker->dateTimeBetween($start->format('Y-m-d H:i:s'),
                //    date_add($end, date_interval_create_from_date_string("8 hours")))
                ? date_add($end, date_interval_create_from_date_string("8 hour"))
                : null,
            'driver_id' => $this->faker->randomElement($driversIds),
        ];
    }
}
