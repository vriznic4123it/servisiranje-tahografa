<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Vozilo;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Servis>
 */
class ServisFactory extends Factory
{
    protected $model = \App\Models\Servis::class;

    public function definition(): array
    {
        $vozilo = Vozilo::factory()->create();
        return [
            'vozilo_id' => $vozilo->id,
            'user_id' => $vozilo->user_id, // klijent koji je vlasnik vozila
            'tip_tahografa' => $this->faker->randomElement(['analogni', 'digitalni']),
            'termin' => $this->faker->dateTimeBetween('+1 days', '+30 days'),
            'status' => 'zakazano',
        ];
    }
}
