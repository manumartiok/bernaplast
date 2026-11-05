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
        Schema::create('desarrollos', function (Blueprint $table) {
            $table->id();
            $table->string('foto')->nullable();
            $table->text('texto')->nullable();
            $table->json('pasos')->nullable();
            $table->text('texto2')->nullable();
            $table->string('titulo_matricerias')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desarrollos');
    }
};
