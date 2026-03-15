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
        Schema::create('parents', function (Blueprint $table) {
            $table->id();

            // Lien vers le compte utilisateur
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Identité
            $table->enum('civilite', ['M.', 'Mme', 'Dr'])->nullable();
            $table->string('nom');
            $table->string('prenom');
            $table->enum('lien_parente', ['Pere', 'Mere', 'Tuteur legal', 'Grand-parent', 'Autre'])
                  ->default('Tuteur legal');

            // Contact
            $table->string('telephone');
            $table->string('telephone_secondaire')->nullable();

            // Adresse
            $table->string('adresse');
            $table->string('ville');
            $table->string('arrondissement')->nullable();
            $table->string('code_postal')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};
