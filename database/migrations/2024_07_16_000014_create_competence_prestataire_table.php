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
        Schema::create('competence_prestataire', function (Blueprint $table) {
            $table->primary(['id_competence', 'id_prestataire']);
            $table->foreignId('id_competence')->constrained('competences')->onDelete('cascade');
            $table->foreignId('id_prestataire')->constrained('prestataires')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competence_prestataire');
    }
}; 