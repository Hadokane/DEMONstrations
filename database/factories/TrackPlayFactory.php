<?php

namespace Database\Factories;

use App\Models\Track;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrackPlay>
 */
class TrackPlayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
    return [
        'track_id' => Track::factory(),
        'user_id' => (fake()->boolean(60) ? User::factory() : null),
        'created_at' => now()->subDays(fake()->numberBetween(0,7))->subMinutes(fake()->numberBetween(0, 1440)),
        'updated_at' => now(),
    ];
    }
}
