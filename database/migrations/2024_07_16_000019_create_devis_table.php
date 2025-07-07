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
        Schema::create('devis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_appel_offre')->constrained('appel_offres')->onDelete('cascade');
            $table->foreignId('id_prestataire')->constrained('prestataires')->onDelete('cascade');
            $table->string('reference')->nullable();
            $table->string('statut');
            $table->decimal('total_ht', 15, 2);
            $table->decimal('total_ttc', 15, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devis');
    }
}; 