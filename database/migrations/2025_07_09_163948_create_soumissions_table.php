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
       Schema::create('soumissions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('appel_offre_id')->constrained('appel_offres')->onDelete('cascade');
    $table->foreignId('prestataire_id')->constrained('prestataires')->onDelete('cascade');
    $table->decimal('montant_propose', 12, 2)->nullable();
    $table->text('description')->nullable();
    $table->timestamp('soumis_le')->useCurrent();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soumissions');
    }
};
