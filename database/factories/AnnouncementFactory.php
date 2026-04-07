<?php

namespace Database\Factories;

use App\Models\Announcement;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => $this->faker->randomElement(array_keys(config('store.announcement_types'))),
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(2, true),
            'sticky' => $this->faker->boolean(15),
        ];
    }
}
