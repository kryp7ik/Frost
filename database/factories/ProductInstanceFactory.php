<?php

namespace Database\Factories;

use App\Models\Store\Product;
use App\Models\Store\ProductInstance;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductInstanceFactory extends Factory
{
    protected $model = ProductInstance::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'price' => $this->faker->randomFloat(2, 9.99, 199.99),
            'stock' => $this->faker->numberBetween(0, 50),
            'redline' => $this->faker->numberBetween(2, 10),
            'active' => $this->faker->boolean(90),
            'store' => $this->faker->randomElement([1, 2, 3]),
        ];
    }
}
