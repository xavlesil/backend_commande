<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Competence;

class CompetenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Competence::create(['nom' => 'Plomberie', 'description' => 'Travaux de plomberie générale']);
        Competence::create(['nom' => 'Électricité', 'description' => 'Installation et maintenance électrique']);
        Competence::create(['nom' => 'Matériel Informatique', 'description' => 'Fourniture et maintenance de matériel informatique']);
        Competence::create(['nom' => 'Développement Logiciel', 'description' => 'Création de logiciels sur mesure']);
        Competence::create(['nom' => 'Mobilier', 'description' => 'Fourniture de mobilier de bureau']);
        Competence::create(['nom' => 'Nettoyage Industriel', 'description' => 'Services de nettoyage pour entreprises']);
        Competence::create(['nom' => 'Gardiennage', 'description' => 'Services de sécurité et de gardiennage']);
        Competence::create(['nom' => 'Imprimerie', 'description' => 'Travaux d\'impression et de reprographie']);
    }
} 