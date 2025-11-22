<?php

namespace Database\Seeders;

use App\Models\Track;
use App\Models\TrackPlay;
use App\Models\User;
use Illuminate\Database\Seeder;

class PlaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $tracks = Track::all();

        foreach ($tracks as $track) {
            // Each track gets between 10 and 50 plays
            $playCount = rand(10, 50);
            $playingUsers = $users->random(min($playCount, $users->count()));

            foreach ($playingUsers as $user) {
                TrackPlay::create([
                    'user_id' => $user->id,
                    'track_id' => $track->id,
                    'created_at' => now()->subDays(rand(0, 30))->subMinutes(rand(0, 1440)),
                ]);
            }
        }
    }
}
