<?php

namespace Database\Factories;

use App\Models\Category;
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
            'title' => fake()->sentence(),
            'thumbnail' => fake()->imageUrl(),
            'body' => fake()->sentence(50),
            'status' => fake()->randomElement(['PUBLISH', 'DRAF']),
            'category_id' => Category::factory()->create(),
        ];
    }
}
