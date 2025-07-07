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
        Schema::table('tickets_besoin', function (Blueprint $table) {
            $table->string('reference')->unique()->after('id');
            $table->string('piece_jointe')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets_besoin', function (Blueprint $table) {
            $table->dropColumn(['reference', 'piece_jointe']);
        });
    }
};
