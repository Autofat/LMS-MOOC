<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        User::firstOrCreate(
            ['email' => 'admin@ppsdm.com'],
            [
                'name' => 'Administrator PPSDM',
                'email' => 'admin@ppsdm.com',
                'password' => Hash::make('password123'),
            ]
        );

        // Create default test user
        User::firstOrCreate(
            ['email' => 'user@test.com'],
            [
                'name' => 'Test User',
                'email' => 'user@test.com',
                'password' => Hash::make('password123'),
            ]
        );

        // Create additional demo user
        User::firstOrCreate(
            ['email' => 'demo@ppsdm.com'],
            [
                'name' => 'Demo User',
                'email' => 'demo@ppsdm.com',
                'password' => Hash::make('demo123'),
            ]
        );
    }
}
