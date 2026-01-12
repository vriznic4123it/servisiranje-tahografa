<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servis;
use App\Models\Vozilo;

class ServisSeeder extends Seeder
{
    public function run(): void
    {
        $vozila = Vozilo::all();

        foreach ($vozila as $vozilo) {
            Servis::factory()->create([
                'vozilo_id' => $vozilo->id,
                'user_id' => $vozilo->user_id,
            ]);
        }
    }
}
