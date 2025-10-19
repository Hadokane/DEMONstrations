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

        Track::factory()->count(3)->create([
            'user_id' => $admin->id,
        ]);
    }
}
