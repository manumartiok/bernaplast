<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * El booleano apto_alimentos pasa a un selector: apto = 'alimentos' | 'quimicos' | null.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->string('apto')->nullable()->after('packaging');
        });

        DB::table('productos')->where('apto_alimentos', true)->update(['apto' => 'alimentos']);

        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('apto_alimentos');
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->boolean('apto_alimentos')->default(false)->after('packaging');
        });

        DB::table('productos')->where('apto', 'alimentos')->update(['apto_alimentos' => true]);

        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('apto');
        });
    }
};
