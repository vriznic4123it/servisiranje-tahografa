<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::factory()->admin()->create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
        ]);

        // Serviser
        User::factory()->serviser()->create([
            'name' => 'Serviser Test',
            'email' => 'serviser@test.com',
        ]);

        // Klijent
        User::factory()->klijent()->create([
            'name' => 'Klijent Test',
            'email' => 'klijent@test.com',
        ]);
    }
}
