<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        User::create([
            'name' => 'User KemenLH/BPLH',
            'email' => 'admin@kemenlh.go.id',
            'password' => Hash::make('admin123456'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);
    }
}
