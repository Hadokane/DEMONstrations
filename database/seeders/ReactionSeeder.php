<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Track;
use App\Models\Reaction;
use Illuminate\Database\Seeder;

class ReactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reactionTypes = ['like', 'dislike', 'laugh', 'wow', 'sad'];
        $users = User::all();
        $tracks = Track::all();

        foreach ($tracks as $track) {
            //Each track gets between 5 and 15 reactions
            $reactionCount = rand(5, 15);
            $reactingUsers = $users->random(min($reactionCount, $users->count()));

            foreach ($reactingUsers as $user) {
                Reaction::create([
                    'user_id' => $user->id,
                    'track_id' => $track->id,
                    'type' => $reactionTypes[array_rand($reactionTypes)],
                ]);
            }
        }
    }
}
