<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vozilo;
use App\Models\User;

class VoziloSeeder extends Seeder
{
    public function run(): void
    {
        // Dodajemo 5 vozila za postojeće klijente
        $klijenti = User::where('role', 'klijent')->get();
        foreach ($klijenti as $klijent) {
            Vozilo::factory()->count(2)->create([
                'user_id' => $klijent->id,
            ]);
        }
    }
}
