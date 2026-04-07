<?php

namespace Database\Factories;

use App\Models\Store\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => ucwords($this->faker->words(rand(2, 3), true)),
            'sku' => $this->faker->unique()->regexify('[A-Z]{2}[0-9]{3}'),
            'category' => $this->faker->randomElement(array_keys(config('store.product_categories'))),
            'cost' => $this->faker->randomFloat(2, 2, 50),
        ];
    }
}
