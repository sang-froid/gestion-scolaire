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
        Schema::create('echeances', function (Blueprint $table) {
            $table->id();

            // Lien inscription et parent
            $table->foreignId('inscription_id')
                  ->constrained('inscriptions')
                  ->onDelete('cascade');

            $table->foreignId('parent_id')
                  ->constrained('parents')
                  ->onDelete('cascade');

            $table->foreignId('eleve_id')
                  ->constrained('eleves')
                  ->onDelete('cascade');

            // Détails tranche
            $table->string('libelle');             // ex: 1ère tranche
            $table->decimal('montant', 10, 2);
            $table->date('date_limite');
            $table->enum('statut', ['impayee', 'payee'])->default('impayee');
            $table->timestamp('payee_le')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('echeances');
    }
};
