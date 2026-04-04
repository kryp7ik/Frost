<?php

namespace Database\Factories;

use App\Models\Store\Transfer;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransferFactory extends Factory
{
    protected $model = Transfer::class;

    public function definition(): array
    {
        $fromStore = $this->faker->randomElement([1, 2, 3]);

        return [
            'from_store' => $fromStore,
            'to_store' => $this->faker->randomElement(array_diff([1, 2, 3], [$fromStore])),
            'received' => $this->faker->boolean(60),
        ];
    }
}
