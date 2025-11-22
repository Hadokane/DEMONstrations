<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\User;
use App\Models\Track;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $tracks = Track::all();

        foreach ($tracks as $track) {
            //Each track gets between 3 and 10 comments
            $commentCount = rand(3, 10);
            $commentingUsers = $users->random(min($commentCount, $users->count()));

            foreach ($commentingUsers as $user) {
                Comment::create([
                    'user_id' => $user->id,
                    'track_id' => $track->id,
                    'body' => fake()->sentence(rand(5, 15)),
                    'timestamp_ms' => rand(0, 200000),
                    'created_at' => now()->subDays(rand(0, 30))->subMinutes(rand(0, 1440)),
                ]);
            }
        }
    }
}
