<?php

namespace Database\Factories;

use App\Models\DriversShift;
use App\Models\ShiftBreak;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ShiftBreak>
 */
class ShiftBreakFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $shiftId = $this->faker->randomElement(DriversShift::pluck('id')->toArray());
        $start = $this->faker->dateTimeBetween(DriversShift::find($shiftId, 'start')->start, 'now');
        $end = $start < now()->toDateTime() ? $this->faker->dateTimeBetween($start, 'now') : null;
        return [
            'start' => $start,
            'end' => $end,
            'shift_id' => $shiftId,
        ];
    }
}
