<?php

namespace Database\Factories;

use App\Models\Auth\User;
use App\Models\Store\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShiftFactory extends Factory
{
    protected $model = Shift::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-30 days', 'now');
        $end = (clone $start)->modify('+' . rand(6, 10) . ' hours');
        $clockIn = (clone $start)->modify('+' . rand(0, 5) . ' minutes');
        $clockOut = (clone $end)->modify('-' . rand(0, 5) . ' minutes');

        return [
            'user_id' => User::factory(),
            'store' => $this->faker->randomElement([1, 2, 3]),
            'start' => $start->format('Y-m-d H:i:s'),
            'end' => $end->format('Y-m-d H:i:s'),
            'in' => $clockIn->format('Y-m-d H:i:s'),
            'out' => $clockOut->format('Y-m-d H:i:s'),
        ];
    }
}
