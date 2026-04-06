<?php

namespace Database\Factories;

use App\Models\Store\Discount;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiscountFactory extends Factory
{
    protected $model = Discount::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['amount', 'percent']);
        $redeemable = $this->faker->boolean(40);

        return [
            'name' => ucwords($this->faker->words(rand(2, 3), true)) . ' Discount',
            'type' => $type,
            'filter' => $this->faker->randomElement(['none', 'product', 'liquid']),
            'amount' => $type === 'percent'
                ? $this->faker->randomElement([5, 10, 15, 20, 25, 50])
                : $this->faker->randomFloat(2, 1, 30),
            'approval' => $this->faker->boolean(30),
            'redeemable' => $redeemable,
            'value' => $redeemable ? $this->faker->randomElement([100, 200, 300, 400, 500, 600]) : null,
        ];
    }
}
