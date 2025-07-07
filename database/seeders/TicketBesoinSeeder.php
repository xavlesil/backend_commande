<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Employe;
use App\Models\Service;
use App\Models\TicketBesoin;
use App\Models\TicketCategorie;
use Illuminate\Support\Facades\Hash;

class TicketBesoinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer ou créer un service
        $service = Service::firstOrCreate(['nom' => 'Services Généraux']);
        $roleEmploye = Role::where('nom', 'Employé')->first();
        $categories = TicketCategorie::pluck('id')->toArray();

        // Créer l'employé Koffi
        $koffiEmploye = Employe::firstOrCreate([
            'nom' => 'Koffi',
            'prenom' => 'Kodjo',
            'id_service' => $service->id,
        ]);
        $koffiUser = User::firstOrCreate([
            'email' => 'koffi@cameg.com',
        ], [
            'password' => Hash::make('password'),
            'profil_id' => $koffiEmploye->id,
            'profil_type' => Employe::class,
        ]);
        $koffiUser->roles()->syncWithoutDetaching([$roleEmploye->id]);

        // Tickets pour Koffi
        TicketBesoin::factory(10)->create([
            'id_createur' => $koffiUser->id,
            'id_categorie' => fake()->randomElement($categories),
        ]);

        // Créer 3 employés aléatoires
        for ($i = 0; $i < 3; $i++) {
            $prenom = fake()->firstName();
            $nom = fake()->lastName();
            $employe = Employe::create([
                'nom' => $nom,
                'prenom' => $prenom,
                'id_service' => $service->id,
            ]);
            $user = User::create([
                'email' => strtolower($prenom.'.'.$nom).'@cameg.com',
                'password' => Hash::make('password'),
                'profil_id' => $employe->id,
                'profil_type' => Employe::class,
            ]);
            $user->roles()->attach($roleEmploye->id);
            // Tickets pour cet employé
            TicketBesoin::factory(7)->create([
                'id_createur' => $user->id,
                'id_categorie' => fake()->randomElement($categories),
            ]);
        }
    }
}
