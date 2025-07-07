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
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('id_prestataire')->constrained('prestataires')->onDelete('cascade');
            $table->foreignId('id_devis')->constrained('devis')->onDelete('cascade');
            $table->string('statut');
            $table->decimal('total_ht', 15, 2);
            $table->decimal('total_ttc', 15, 2);
            $table->timestamp('date_livraison_prevue')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
}; 