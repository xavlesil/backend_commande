<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketBesoin>
 */
class TicketBesoinFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $refCount = 1;
        $date = now()->format('Ymd');
        $reference = 'TCK-' . $date . '-' . str_pad($refCount++, 4, '0', STR_PAD_LEFT);
        $priorites = ['basse', 'normal', 'haute', 'urgente'];
        $statuts = ['NOUVEAU', 'EN COURS', 'RESOLU', 'CLOS'];
        return [
            'reference' => $reference,
            'titre' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'piece_jointe' => null,
            'statut' => $this->faker->randomElement($statuts),
            'id_createur' => 1, // à surcharger dans le seeder
            'id_categorie' => 1, // à surcharger dans le seeder
            'priorite' => $this->faker->randomElement($priorites),
            'date_resolution' => null,
        ];
    }
}
