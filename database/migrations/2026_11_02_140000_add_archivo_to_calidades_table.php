<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * PDF de la política de calidad (botón "Descargar política de calidad").
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('calidades', function (Blueprint $table) {
            $table->string('archivo')->nullable()->after('foto');
        });
    }

    public function down(): void
    {
        Schema::table('calidades', function (Blueprint $table) {
            $table->dropColumn('archivo');
        });
    }
};
