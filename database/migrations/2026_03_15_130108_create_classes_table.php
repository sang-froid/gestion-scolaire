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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');                   // ex: CP1-A
            $table->string('niveau');                // ex: CP1
            $table->string('annee_scolaire');        // ex: 2025-2026
            $table->unsignedInteger('capacite_max')->default(30);
            $table->string('enseignant_responsable')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            // Une seule classe de ce nom par année scolaire
            $table->unique(['nom', 'annee_scolaire']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
