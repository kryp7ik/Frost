<?php

namespace Database\Factories;

use App\Models\Store\LiquidProduct;
use App\Models\Store\Recipe;
use App\Models\Store\ShopOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class LiquidProductFactory extends Factory
{
    protected $model = LiquidProduct::class;

    public function definition(): array
    {
        return [
            'shop_order_id' => ShopOrder::factory(),
            'recipe_id' => Recipe::factory(),
            'store' => $this->faker->randomElement([1, 2, 3]),
            'size' => $this->faker->randomElement([10, 30, 50, 100]),
            'nicotine' => $this->faker->randomElement([0, 3, 6, 12, 18, 24, 50]),
            'vg' => $this->faker->randomElement([40, 60, 100]),
            'menthol' => $this->faker->randomElement([0, 1, 2, 3, 4]),
            'extra' => $this->faker->boolean(20),
            'mixed' => $this->faker->boolean(80),
            'salt' => $this->faker->boolean(30),
        ];
    }
}
