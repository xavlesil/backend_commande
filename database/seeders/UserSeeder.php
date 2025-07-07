<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Employe;
use App\Models\Prestataire;
use App\Models\Service;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Créer un service par défaut
        $service = Service::create(['nom' => 'Services Généraux']);

        // 2. Créer l'employé Chef SG
        $chefSgEmploye = Employe::create([
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'id_service' => $service->id,
        ]);
        // Le user associé
        $chefSgUser = User::create([
            'email' => 'chef.sg@cameg.com',
            'password' => Hash::make('password'),
            'profil_id' => $chefSgEmploye->id,
            'profil_type' => Employe::class,
        ]);
        // Attribuer le rôle Chef SG
        $roleChefSg = Role::where('nom', 'Chef SG')->first();
        $chefSgUser->roles()->attach($roleChefSg);


        // 3. Créer un employé standard
        $employeStandard = Employe::create([
            'nom' => 'Martin',
            'prenom' => 'Alice',
            'id_service' => $service->id,
        ]);
        // Le user associé
        $employeUser = User::create([
            'email' => 'employe@cameg.com',
            'password' => Hash::make('password'),
            'profil_id' => $employeStandard->id,
            'profil_type' => Employe::class,
        ]);
        // Attribuer le rôle Employé
        $roleEmploye = Role::where('nom', 'Employé')->first();
        $employeUser->roles()->attach($roleEmploye);


        // 4. Créer un prestataire
        $prestataire = Prestataire::create([
            'nom_commercial' => 'Presta-Info Services',
            'raison_sociale' => 'Presta-Info SARL',
            'nif' => '123456789',
            'adresse' => '123 Rue de la République',
        ]);
        // Le user associé
        $prestataireUser = User::create([
            'email' => 'contact@presta-info.com',
            'password' => Hash::make('password'),
            'profil_id' => $prestataire->id,
            'profil_type' => Prestataire::class,
        ]);
        // Attribuer le rôle Prestataire
        $rolePrestataire = Role::where('nom', 'Prestataire')->first();
        $prestataireUser->roles()->attach($rolePrestataire);
    }
} 