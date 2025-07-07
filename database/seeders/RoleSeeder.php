<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['nom' => 'Employé', 'user_type' => 'interne']);
        Role::create(['nom' => 'Chef SG', 'user_type' => 'interne']);
        Role::create(['nom' => 'Superviseur', 'user_type' => 'interne']);
        Role::create(['nom' => 'Prestataire', 'user_type' => 'externe']);
    }
} 