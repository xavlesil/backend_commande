<?php

namespace Database\Seeders;

use App\Models\Competence;
use App\Models\Prestataire;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrestataireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // On s'assure que les compétences sont chargées
        if (Competence::count() == 0) {
            $this->call(CompetenceSeeder::class);
        }

        $competences = Competence::all();

        if ($competences->isEmpty()) {
            // Ne rien faire si aucune compétence n'existe
            return;
        }

        // Créer 25 prestataires
        Prestataire::factory(25)->create()->each(function ($prestataire) use ($competences) {
            // Attribuer entre 1 et 3 compétences au hasard à chaque prestataire
            $prestataire->competences()->attach(
                $competences->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
