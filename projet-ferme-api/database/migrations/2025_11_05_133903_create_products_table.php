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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Utilisation de l'helper foreignId() et constrained()
            // Laravel devine automatiquement la table de référence ('categories')
            // et la colonne ('id'). Par défaut, il ajoute onDelete('cascade').
            $table->foreignId('category_id')->constrained();

            $table->string('nom');
            $table->string('description')->nullable();
            $table->float('prix_base');
            $table->float('prix_etudiant')->nullable();
            $table->integer('stock');
            $table->enum('statut', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
