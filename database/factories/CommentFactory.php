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
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'track_id' => Track::inRandomOrder()->first()->id ?? Track::factory(),
            'body' => fake()->sentences(mt_rand(1,3), true),
            'timestamp_ms' => fake()->numberBetween(0, 200000),
        ];
    }
}
