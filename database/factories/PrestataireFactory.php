<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prestataire>
 */
class PrestataireFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom_commercial' => $this->faker->company,
            'raison_sociale' => $this->faker->companySuffix . ' ' . $this->faker->company,
            'nif' => $this->faker->unique()->numerify('#########'), // NIF togolais à 9 chiffres
            'adresse' => $this->faker->address,
            'latitude' => $this->faker->latitude(6.1, 6.4),
            'longitude' => $this->faker->longitude(1.1, 1.4),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
