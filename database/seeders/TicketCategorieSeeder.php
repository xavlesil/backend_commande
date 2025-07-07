<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TicketCategorie;

class TicketCategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TicketCategorie::create(['nom' => 'Plomberie']);
        TicketCategorie::create(['nom' => 'Électricité']);
        TicketCategorie::create(['nom' => 'Informatique']);
        TicketCategorie::create(['nom' => 'Mobilier de bureau']);
        TicketCategorie::create(['nom' => 'Fournitures de bureau']);
        TicketCategorie::create(['nom' => 'Entretien et Nettoyage']);
        TicketCategorie::create(['nom' => 'Sécurité']);
    }
} 