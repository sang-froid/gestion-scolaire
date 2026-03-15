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
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id();

            // Liens
            $table->foreignId('eleve_id')
                  ->constrained('eleves')
                  ->onDelete('cascade');

            // Classe affectée (nullable jusqu'à l'affectation par l'admin)
            $table->foreignId('classe_id')
                  ->nullable()
                  ->constrained('classes')
                  ->onDelete('set null');

            // Infos dossier
            $table->string('numero_dossier')->unique(); // ex: INS-2026-0042
            $table->string('annee_scolaire');           // ex: 2025-2026
            $table->enum('type', ['nouvelle', 'reinscription'])->default('nouvelle');

            // Statut du dossier
            $table->enum('statut', ['en_attente', 'validee', 'refusee'])
                  ->default('en_attente');
            $table->text('motif_refus')->nullable();    // si refusée
            $table->timestamp('validee_le')->nullable();
            $table->foreignId('validee_par')            // admin qui a validé
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            $table->timestamps();

            // Un élève ne peut avoir qu'une seule inscription par année
            $table->unique(['eleve_id', 'annee_scolaire']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};
