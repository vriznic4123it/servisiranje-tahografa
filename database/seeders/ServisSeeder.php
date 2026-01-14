<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servis;
use App\Models\User;
use App\Models\Vozilo;

class ServisSeeder extends Seeder
{
    public function run(): void
    {
        // Uzimamo prvog klijenta
        $user = User::where('role', 'klijent')->first();
        
        if (!$user) {
            // Kreiraj klijenta ako ne postoji
            $user = User::factory()->create([
                'role' => 'klijent',
                'name' => 'Test Klijent',
                'email' => 'klijent@test.com',
                'password' => bcrypt('password')
            ]);
        }

        // Uzimamo ili kreiramo prvo vozilo za klijenta
        $vozilo = Vozilo::firstOrCreate(
            ['registracija' => 'BG123AB'],
            [
                'marka' => 'Torp', 
                'model' => 'X1',
                'user_id' => $user->id
            ]
        );

        // prvo vozilo i servis
        Servis::create([
            'user_id' => $user->id,
            'vozilo_id' => $vozilo->id,
            'vozilo' => $vozilo->registracija . ' - ' . $vozilo->marka . ' ' . $vozilo->model,
            'tip_tahografa' => 'analogni',
            'opis_problema' => 'Test problem',
            'termin' => now()->addDays(3),
            'telefon' => '0631234567',
            'status' => 'zakazano',
        ]);

        $vozilo2 = Vozilo::firstOrCreate(
            ['registracija' => 'BG456CD'],
            [
                'marka' => 'Franecki', 
                'model' => 'Z2',
                'user_id' => $user->id
            ]
        );

        // Drugo vozilo i servis
        Servis::create([
            'user_id' => $user->id,
            'vozilo_id' => $vozilo2->id,
            'vozilo' => $vozilo2->registracija . ' - ' . $vozilo2->marka . ' ' . $vozilo2->model,
            'tip_tahografa' => 'digitalni',
            'opis_problema' => 'Test problem 2',
            'termin' => now()->addDays(5),
            'telefon' => '0639876543',
            'status' => 'zakazano',
        ]);
    }
}