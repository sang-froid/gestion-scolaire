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
        Schema::create('eleves', function (Blueprint $table) {
            $table->id();

            // Lien vers le parent responsable
            $table->foreignId('parent_id')
                  ->constrained('parents')
                  ->onDelete('cascade');

            // Identité
            $table->string('nom');
            $table->string('prenom');
            $table->date('date_naissance');
            $table->string('lieu_naissance')->nullable();
            $table->enum('sexe', ['M', 'F']);
            $table->string('nationalite')->default('Béninoise');
            $table->string('numero_acte_naissance')->nullable();

            // Photo
            $table->string('photo')->nullable(); // chemin storage

            // Scolarité
            $table->string('niveau_souhaite')->nullable();
            $table->string('ancienne_ecole')->nullable();
            $table->string('derniere_classe')->nullable();
            $table->enum('resultat_precedent', ['Admis(e)', 'Ajourné(e)', '1ère scolarisation'])
                  ->nullable();

            // Santé
            $table->enum('groupe_sanguin', ['A+','A-','B+','B-','O+','O-','AB+','AB-'])
                  ->nullable();
            $table->text('infos_medicales')->nullable();

            // Matricule généré après validation
            $table->string('matricule')->unique()->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eleves');
    }
};
