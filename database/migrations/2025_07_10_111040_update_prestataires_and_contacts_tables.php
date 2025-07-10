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
        // === Modifications sur la table 'prestataires' ===
        Schema::table('prestataires', function (Blueprint $table) {
            // Renommer les colonnes pour la cohérence
            $table->renameColumn('raison_sociale', 'nom_officiel');
            $table->renameColumn('adresse', 'adresse_siege');

            // Ajouter les nouvelles colonnes
            $table->string('email_generique')->nullable()->unique()->after('nif');
            $table->string('telephone_standard')->nullable()->after('email_generique');
            $table->string('site_web')->nullable()->after('telephone_standard');
            $table->enum('statut', ['ACTIF', 'INACTIF', 'EN_VALIDATION', 'REFUSE'])
                  ->default('EN_VALIDATION')
                  ->after('site_web');
        });

        // === Modifications sur la table 'contacts_prestataire' ===
        Schema::table('contacts_prestataire', function (Blueprint $table) {
            // Renommer la colonne pour corriger l'erreur 500
            $table->renameColumn('is_default', 'est_contact_principal');
        });
    }

    /**
     * Reverse the migrations.
     * Cette méthode définit comment annuler les changements de la méthode up().
     */
    public function down(): void
    {
        // === Annuler les modifications sur 'prestataires' ===
        Schema::table('prestataires', function (Blueprint $table) {
            // Renommer les colonnes dans l'ordre inverse
            $table->renameColumn('nom_officiel', 'raison_sociale');
            $table->renameColumn('adresse_siege', 'adresse');

            // Supprimer les colonnes ajoutées
            $table->dropColumn(['email_generique', 'telephone_standard', 'site_web', 'statut']);
        });

        // === Annuler les modifications sur 'contacts_prestataire' ===
        Schema::table('contacts_prestataire', function (Blueprint $table) {
            // Renommer la colonne dans l'ordre inverse
            $table->renameColumn('est_contact_principal', 'is_default');
        });
    }
};