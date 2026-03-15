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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            // Lien vers l'inscription concernée
            $table->foreignId('inscription_id')
                  ->constrained('inscriptions')
                  ->onDelete('cascade');

            // Type de document
            $table->enum('type', [
                'acte_naissance',
                'bulletin',
                'certificat_medical',
                'piece_identite_parent',
                'photo_identite',
                'fiche_inscription',  // généré par le système
                'carte_scolarite',    // généré par le système
            ]);

            $table->string('nom_fichier');       // nom original
            $table->string('chemin_fichier');    // storage/...
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('taille')->nullable(); // en octets
            $table->boolean('genere_systeme')->default(false); // fiche/carte auto
            $table->timestamp('genere_le')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
