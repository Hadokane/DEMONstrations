<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Track>
 */
class TrackFactory extends Factory
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
            'title' => fake()->sentence(3),
            'artist' => fake()->name(),
            'audio_file_path' => 'tracks/'.fake()->uuid().'.mp3',
            'cover_image_path' => null,
            'duration_ms' => fake()->numberBetween(60000, 240000),
            'visibility' => 'public',
            'play_count' =>  fake()->numberBetween(0, 5000),
        ];
    }
}
