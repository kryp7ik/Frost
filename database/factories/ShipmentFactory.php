<?php

namespace Database\Factories;

use App\Models\Store\Shipment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShipmentFactory extends Factory
{
    protected $model = Shipment::class;

    public function definition(): array
    {
        return [
            'store' => $this->faker->randomElement([1, 2, 3]),
        ];
    }
}
