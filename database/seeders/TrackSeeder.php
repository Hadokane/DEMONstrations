<?php

namespace Database\Seeders;

use App\Models\Track;
use App\Models\User;
use Illuminate\Database\Seeder;

class TrackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email','admin@example.com')->firstOrFail();

        // Public track (everyone can see)
        Track::factory()->create([
            'user_id'    => $admin->id,
            'title'      => 'Public Demo Track',
            'visibility' => 'public',
        ]);

        // Private track (owner-only)
        Track::factory()->create([
            'user_id'    => $admin->id,
            'title'      => 'Private Demo Track',
            'visibility' => 'private',
        ]);

        // 3 Additional tracks
        Track::factory()->count(3)->create([
            'user_id' => $admin->id,
        ]);
    }
}
