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

        // Uzimamo ili kreiramo prvo vozilo za klijenta
        $vozilo = Vozilo::firstOrCreate(
            ['registracija' => 'BG123AB', 'user_id' => $user->id],
            ['marka' => 'Torp', 'model' => 'X1']
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
            ['registracija' => 'BG456CD', 'user_id' => $user->id],
            ['marka' => 'Franecki', 'model' => 'Z2']
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

        // Treće vozilo i servis
        $vozilo3 = Vozilo::firstOrCreate(
            ['registracija' => 'BG789EF', 'user_id' => $user->id],
            ['marka' => 'Crist', 'model' => 'M3']
        );

        Servis::create([
            'user_id' => $user->id,
            'vozilo_id' => $vozilo3->id,
            'vozilo' => $vozilo3->registracija . ' - ' . $vozilo3->marka . ' ' . $vozilo3->model,
            'tip_tahografa' => 'analogni',
            'opis_problema' => 'Test problem 3',
            'termin' => now()->addDays(7),
            'telefon' => '0631112222',
            'status' => 'zakazano',
        ]);
    }
}
