<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
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
        // return [
        //     'name' => fake()->name(),
        //     'email' => fake()->unique()->safeEmail(),
        //     'email_verified_at' => now(),
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10),
        // ];

        return [
            'creator_id' => fake()->numberBetween(1, 5),
            'account_type' => fake()->randomElement(['facebook', 'twitter', 'youtube']),
            'account_id' => fake()->word,
            'account_name' => fake()->word,
            'status' => fake()->randomElement(['pending', 'published']),
            'thumbnail' => fake()->text,
            'link' => fake()->text,
            'post_title' => fake()->text,
            'content' => fake()->text,
            'youtube_privacy' => fake()->randomElement(['public', 'private']),
            'youtube_tags' => fake()->text,
            'youtube_category' => fake()->numberBetween(1, 10), // Assuming you have 10 categories
            'scheduledTime' => fake()->dateTimeThisMonth,
            'tokenApp' => fake()->unique()->text(500),
            'token_secret' => fake()->unique()->text(500),
        ];
    }
}
