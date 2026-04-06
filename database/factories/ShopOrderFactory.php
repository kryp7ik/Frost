<?php

namespace Database\Factories;

use App\Models\Auth\User;
use App\Models\Store\Customer;
use App\Models\Store\ShopOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShopOrderFactory extends Factory
{
    protected $model = ShopOrder::class;

    public function definition(): array
    {
        $subtotal = $this->faker->randomFloat(2, 5, 300);

        return [
            'store' => $this->faker->randomElement([1, 2, 3]),
            'customer_id' => Customer::factory(),
            'user_id' => User::factory(),
            'subtotal' => $subtotal,
            'total' => round($subtotal * (1 + config('store.tax', 0.06)), 2),
            'complete' => $this->faker->boolean(75),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn () => ['complete' => true]);
    }

    public function incomplete(): static
    {
        return $this->state(fn () => ['complete' => false]);
    }
}
