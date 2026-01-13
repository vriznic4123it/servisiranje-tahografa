<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::factory()->admin()->create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password')
        ]);

        // Serviser
        User::factory()->serviser()->create([
            'name' => 'Serviser Test',
            'email' => 'serviser@test.com',
            'password' => Hash::make('password')
        ]);

        // Klijent
        User::factory()->klijent()->create([
            'name' => 'Klijent Test',
            'email' => 'klijent@test.com',
            'password' => Hash::make('password')
        ]);
    }
}
