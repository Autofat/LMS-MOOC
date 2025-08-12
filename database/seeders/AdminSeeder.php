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
            'name' => 'AdminKemenLH/BPLH',
            'email' => 'superadmin@elearning.kemenlh.go.id',
            'password' => Hash::make('kemenlh.go.id'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);
    }
}
