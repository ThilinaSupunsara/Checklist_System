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
    public function run()
    {
        // Create the main Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        // Create a sample Owner/User
        User::create([
            'name' => 'Sample Owner',
            'email' => 'owner@example.com',
            'role' => 'owner',
            'password' => Hash::make('password'),
        ]);

        // Create a sample Housekeeper
        User::create([
            'name' => 'Sample Housekeeper',
            'email' => 'housekeeper@example.com',
            'role' => 'housekeeper',
            'password' => Hash::make('password'),
        ]);
    }
}
