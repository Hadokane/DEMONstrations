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
                'first_name' => 'Demon',
                'last_name' => 'Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        // Hard-Code some Reviewers
        User::updateOrCreate(
            ['email' => 'reviewer1@example.com'],
            [
                'first_name' => 'Renny',
                'last_name' => 'Reviewer',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        );

        User::updateOrCreate(
            ['email' => 'reviewer2@example.com'],
            [
                'first_name' => 'Rita',
                'last_name' => 'Reviewer',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        );
    }
}
