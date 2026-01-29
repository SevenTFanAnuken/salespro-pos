<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // Check if this exists
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'), // Set your desired password
                'email_verified_at' => now(),
                'role' => 'admin',
            ]
        );
    }
}