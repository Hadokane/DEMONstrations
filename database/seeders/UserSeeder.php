<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hard-Code an Admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'artist_name' => 'DemonAdmin',
                'first_name' => 'Demon',
                'last_name' => 'Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        // Hard-Code some Reviewers (non-admins)
        User::updateOrCreate(
            ['email' => 'reviewer1@example.com'],
            [
                'artist_name' => 'RennyReviewer',
                'first_name' => 'Renny',
                'last_name' => 'Reviewer',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        );

        User::updateOrCreate(
            ['email' => 'reviewer2@example.com'],
            [
                'artist_name' => 'RitaReviewer',
                'first_name' => 'Rita',
                'last_name' => 'Reviewer',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        );

        // Generate additional random users
        User::factory()->count(20)->create();
    }
}
