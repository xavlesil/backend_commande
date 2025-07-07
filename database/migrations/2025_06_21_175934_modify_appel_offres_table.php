<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appel_offres', function (Blueprint $table) {
            // Renommer les colonnes
            $table->renameColumn('objet', 'titre');
            $table->renameColumn('cahier_des_charges', 'description');
            
            // Ajouter la nouvelle colonne de date avant de supprimer l'ancienne
            $table->date('date_cloture')->after('description');
            $table->dropColumn('date_limite');

            // Gérer la clé étrangère
            $table->dropForeign(['id_createur']);
            $table->renameColumn('id_createur', 'id_gestionnaire');
            $table->foreign('id_gestionnaire')->references('id')->on('users')->onDelete('cascade');

            // Supprimer la colonne 'reference'
            $table->dropColumn('reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appel_offres', function (Blueprint $table) {
            // Inverser les opérations
            $table->renameColumn('titre', 'objet');
            $table->renameColumn('description', 'cahier_des_charges');

            $table->timestamp('date_limite');
            $table->dropColumn('date_cloture');
            
            $table->dropForeign(['id_gestionnaire']);
            $table->renameColumn('id_gestionnaire', 'id_createur');
            $table->foreign('id_createur')->references('id')->on('users')->onDelete('cascade');
            
            $table->string('reference')->unique()->after('id');
        });
    }
};
