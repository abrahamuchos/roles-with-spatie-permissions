<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fn() => User::factory()->create()->id,
            'title' => fake()->sentence(5),
            'slug' => fake()->slug(),
            'content' => fake()->paragraph(3),
            'image' => fake()->image,
            'status' => fake()->randomElement(['published', 'draft']),
        ];
    }
}
