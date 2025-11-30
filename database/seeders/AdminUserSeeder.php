<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un utilisateur admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@almesta.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'points' => 1000,
            'total_spent' => 0,
            'email_verified_at' => now(),
        ]);
        
        // Créer quelques utilisateurs normaux
        User::create([
            'name' => 'Utilisateur Test',
            'email' => 'user@test.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'points' => 250,
            'total_spent' => 150.50,
            'email_verified_at' => now(),
        ]);
        
        User::create([
            'name' => 'Marie Martin',
            'email' => 'marie@test.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'points' => 500,
            'total_spent' => 320.80,
            'email_verified_at' => now(),
        ]);
    }
}
