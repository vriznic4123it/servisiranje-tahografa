<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Vozilo;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vozilo>
 */
class VoziloFactory extends Factory
{
    protected $model = Vozilo::class;

    public function definition(): array
    {
        return [
            'user_id' => User::where('role', 'klijent')->inRandomOrder()->first()->id,
            'registracija' => strtoupper($this->faker->unique()->bothify('BG###??')),
            'marka' => $this->faker->company(),
            'model' => $this->faker->word(),
        ];
    }
}
