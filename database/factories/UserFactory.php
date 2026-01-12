<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = \App\Models\User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // lozinka: password
            'remember_token' => Str::random(10),
            'role' => 'klijent', // default role
        ];
    }

    // Factory za Admin
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    // Factory za Servisera
    public function serviser(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'serviser',
        ]);
    }

    // Factory za Klijenta
    public function klijent(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'klijent',
        ]);
    }
}
