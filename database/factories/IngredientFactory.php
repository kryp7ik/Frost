<?php

namespace Database\Factories;

use App\Models\Store\Ingredient;
use Illuminate\Database\Eloquent\Factories\Factory;

class IngredientFactory extends Factory
{
    protected $model = Ingredient::class;

    private static array $flavorNames = [
        'Strawberry', 'Blueberry', 'Watermelon', 'Mango', 'Peach', 'Grape', 'Apple',
        'Pineapple', 'Cherry', 'Raspberry', 'Lemon', 'Lime', 'Orange', 'Banana',
        'Coconut', 'Vanilla', 'Caramel', 'Butterscotch', 'Cinnamon', 'Menthol',
        'Spearmint', 'Peppermint', 'Tobacco', 'Coffee', 'Chocolate', 'Hazelnut',
        'Cream', 'Custard', 'Cheesecake', 'Cookie', 'Graham Cracker', 'Marshmallow',
        'Cotton Candy', 'Bubblegum', 'Gummy Bear', 'Dragonfruit', 'Guava', 'Kiwi',
        'Passion Fruit', 'Jackfruit', 'Honeydew', 'Cantaloupe', 'Pomegranate',
        'Blackberry', 'Cranberry', 'Almond', 'Pistachio', 'Maple', 'Honey', 'Yogurt',
    ];

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(self::$flavorNames),
            'vendor' => $this->faker->randomElement(['TPA', 'CAP', 'FW', 'FA', 'INW', 'LB', 'RF', 'NF', 'VT', 'WF']),
        ];
    }
}
