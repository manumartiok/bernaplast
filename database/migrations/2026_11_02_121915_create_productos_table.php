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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('orden')->nullable();
            $table->string('nombre')->nullable();
            $table->string('foto')->nullable();
            $table->text('caracteristicas')->nullable();
            $table->text('medidas')->nullable();
            $table->string('packaging')->nullable();
            $table->boolean('apto_alimentos')->default(false);
            $table->string('nota')->nullable();
            $table->json('tapas_guarnicion')->nullable();
            $table->json('tapas_precinto')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('destacado')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
