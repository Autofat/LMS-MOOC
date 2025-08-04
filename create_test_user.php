<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Bootstrap Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Check if user already exists
    $existingUser = User::where('email', 'test@test.com')->first();
    
    if ($existingUser) {
        echo "User already exists: test@test.com\n";
        echo "You can login with email: test@test.com and password: password\n";
    } else {
        // Create new user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        
        echo "Test user created successfully!\n";
        echo "Email: test@test.com\n";
        echo "Password: password\n";
        echo "\nYou can now login at: http://localhost:8000/login\n";
    }
} catch (Exception $e) {
    echo "Error creating user: " . $e->getMessage() . "\n";
}
