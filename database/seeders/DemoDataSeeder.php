<?php

namespace Database\Seeders;

use App\Models\{Comment, Track, TrackAccess, TrackPlay, User, Reaction};
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email','admin@example.com')->first();
        $r1    = User::where('email','reviewer1@example.com')->first();
        $r2    = User::where('email','reviewer2@example.com')->first();

        $tracks = Track::all();

        foreach ($tracks as $track) {

            // Access granted to reviewers
            TrackAccess::updateOrCreate(
                ['track_id' => $track->id, 'user_id' => $r1->id],
                ['granted_by' => $admin->id]
            );

            TrackAccess::updateOrCreate(
                ['track_id' => $track->id, 'user_id' => $r2->id],
                ['granted_by' => $admin->id]
            );

            // Plays
            TrackPlay::factory()->count(rand(15, 100))->create([
                'track_id' => $track->id,
            ]);

            // Reactions — one per reviewer
            $reactionTypes = ['like', 'dislike', 'laugh', 'wow', 'sad'];
            Reaction::updateOrCreate(
                ['user_id' => $r1->id, 'track_id' => $track->id],
                ['type' => $reactionTypes[array_rand($reactionTypes)]]
            );

            Reaction::updateOrCreate(
                ['user_id' => $r2->id, 'track_id' => $track->id],
                ['type' => $reactionTypes[array_rand($reactionTypes)]]
            );

            // Add extra reactions from other generated users
            $extraUsers = User::whereNotIn('id', [$admin->id, $r1->id, $r2->id])->get();
            foreach ($extraUsers as $user) {
                Reaction::updateOrCreate(
                    ['user_id' => $user->id, 'track_id' => $track->id],
                    ['type' => $reactionTypes[array_rand($reactionTypes)]]
                );
            }

            // Comments
            Comment::factory()->count(rand(3,12))->create([
                'track_id' => $track->id,
                'user_id'  => rand(0,1) ? $r1->id : $r2->id,
            ]);
        }
    }
}
