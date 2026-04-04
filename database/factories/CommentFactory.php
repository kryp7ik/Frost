<?php

namespace Database\Factories;

use App\Models\Announcement;
use App\Models\Auth\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'announcement_id' => Announcement::factory(),
            'content' => $this->faker->paragraph(),
        ];
    }
}
