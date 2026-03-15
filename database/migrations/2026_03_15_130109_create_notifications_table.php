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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // Qui a envoyé (admin)
            $table->foreignId('envoyee_par')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            // Destinataire (parent)
            $table->foreignId('parent_id')
                  ->constrained('parents')
                  ->onDelete('cascade');

            // Elève concerné (optionnel — null = concerne tous les enfants)
            $table->foreignId('eleve_id')
                  ->nullable()
                  ->constrained('eleves')
                  ->onDelete('set null');

            // Contenu
            $table->string('sujet');
            $table->text('message');
            $table->enum('type', ['paiement', 'urgente', 'info'])->default('info');

            // Envoi email
            $table->boolean('email_envoye')->default(false);
            $table->timestamp('email_envoye_le')->nullable();

            // Lecture
            $table->timestamp('lu_le')->nullable(); // null = non lue

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
