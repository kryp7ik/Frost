<?php

namespace Database\Factories;

use App\Models\Store\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipeFactory extends Factory
{
    protected $model = Recipe::class;

    private static array $flavorPrefixes = [
        'Midnight', 'Tropical', 'Icy', 'Golden', 'Crystal', 'Velvet', 'Solar', 'Cosmic',
        'Arctic', 'Royal', 'Mystic', 'Thunder', 'Cloud', 'Frost', 'Blazing', 'Neon',
        'Shadow', 'Dream', 'Wild', 'Sweet', 'Twisted', 'Electric', 'Savage', 'Smooth',
    ];

    private static array $flavorSuffixes = [
        'Blast', 'Rush', 'Wave', 'Storm', 'Breeze', 'Surge', 'Chill', 'Fusion',
        'Burst', 'Mist', 'Drop', 'Twist', 'Punch', 'Splash', 'Cloud', 'Haze',
        'Frost', 'Fire', 'Bliss', 'Dream', 'Crush', 'Swirl', 'Daze', 'Kick',
    ];

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(self::$flavorPrefixes) . ' ' . $this->faker->randomElement(self::$flavorSuffixes),
            'sku' => $this->faker->unique()->regexify('E[0-9]{3}'),
            'active' => $this->faker->boolean(85),
        ];
    }
}
