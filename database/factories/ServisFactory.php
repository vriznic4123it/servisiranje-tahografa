<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Vozilo;

class ServisFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'vozilo_id' => Vozilo::factory(),
            'vozilo' => fake()->word() . ' - ' . fake()->word(),
            'tip_tahografa' => fake()->randomElement(['analogni', 'digitalni']),
            'opis_problema' => fake()->sentence(),
            'termin' => fake()->dateTimeBetween('now', '+30 days'),
            'telefon' => fake()->phoneNumber(),
            'status' => fake()->randomElement(['zakazano', 'u_radu', 'zavrseno']),
        ];
    }
}