<?php

namespace Database\Factories;

use App\Models\Store\Payment;
use App\Models\Store\ShopOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'shop_order_id' => ShopOrder::factory(),
            'type' => $this->faker->randomElement(['cash', 'card']),
            'amount' => $this->faker->randomFloat(2, 5, 300),
        ];
    }
}
