<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Track;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'track_id' => Track::factory(),
            'body' => fake()->sentences(mt_rand(1,3), true),
            'timestamp_ms' => fake()->boolean(70) ? fake()->numberBetween(0, 180_000) : null,
        ];
    }
}
