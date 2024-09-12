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
        Schema::create('dette_article', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dette_id')->constrained();
            $table->foreignId('article_id')->constrained();
            $table->float('quantite');
            $table->float('prix_vente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dette_article');
    }
};
