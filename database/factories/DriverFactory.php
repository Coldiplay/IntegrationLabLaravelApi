<?php

namespace Database\Factories;

use App\Enums\Rights;
use App\Enums\Role;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Driver>
 */
class DriverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ids = array_diff(User::where('role', Role::DRIVER)
            ->orWhere('role', Role::DEFAULT_DRIVER)
            ->pluck('id')->toArray(),
            Driver::pluck('user_id')->toArray());
        return [
            'user_id' => $this->faker->unique()->randomElement($ids),
            'rights' => $this->faker->randomElement(Rights::getValues()),
            'drivers_license' => $this->faker->text(40),
        ];
    }
}
