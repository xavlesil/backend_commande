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
        Schema::create('appel_offre_competence', function (Blueprint $table) {
            $table->primary(['id_appel_offre', 'id_competence']);
            $table->foreignId('id_appel_offre')->constrained('appel_offres')->onDelete('cascade');
            $table->foreignId('id_competence')->constrained('competences')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appel_offre_competence');
    }
}; 