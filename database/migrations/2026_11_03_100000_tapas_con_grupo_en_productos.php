<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Las tapas pasan de dos listas fijas (tapas_guarnicion / tapas_precinto) a una sola lista
 * con el grupo de cada fila, para soportar todos los tipos de tapa del Excel de productos
 * (rosca o presión, con/sin guarnición, con/sin precinto, con/sin manija).
 *
 * tapas = [['grupo' => 'Tapa a rosca con guarnición sin precinto', 'color' => 'Amarillo', 'codigo' => '330-TR-CGSP-A', 'foto' => ..., 'descripcion' => ...], ...]
 * codigo = código del envase (clave para la importación desde Excel)
 */
return new class extends Migration
{
    private array $grupos = [
        'tapas_guarnicion' => 'Tapa a rosca con guarnición sin precinto',
        'tapas_precinto'   => 'Tapa a rosca sin guarnición con precinto',
    ];

    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->string('codigo')->nullable()->unique()->after('familia_id');
            $table->json('tapas')->nullable()->after('nota');
        });

        foreach (DB::table('productos')->get() as $producto) {
            $tapas = [];
            foreach ($this->grupos as $columna => $grupo) {
                foreach (json_decode($producto->{$columna} ?? '[]', true) ?: [] as $fila) {
                    $tapas[] = ['grupo' => $grupo, 'color' => $fila['color'] ?? null, 'codigo' => null, 'foto' => $fila['foto'] ?? null, 'descripcion' => $fila['descripcion'] ?? null];
                }
            }
            DB::table('productos')->where('id', $producto->id)->update(['tapas' => $tapas ? json_encode($tapas, JSON_UNESCAPED_UNICODE) : null]);
        }

        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['tapas_guarnicion', 'tapas_precinto']);
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->json('tapas_guarnicion')->nullable();
            $table->json('tapas_precinto')->nullable();
        });

        foreach (DB::table('productos')->get() as $producto) {
            $filas = collect(json_decode($producto->tapas ?? '[]', true) ?: []);
            $datos = [];
            foreach ($this->grupos as $columna => $grupo) {
                $grupoFilas = $filas->where('grupo', $grupo)->map(fn ($f) => ['color' => $f['color'] ?? null, 'foto' => $f['foto'] ?? null, 'descripcion' => $f['descripcion'] ?? null])->values()->all();
                $datos[$columna] = $grupoFilas ? json_encode($grupoFilas, JSON_UNESCAPED_UNICODE) : null;
            }
            DB::table('productos')->where('id', $producto->id)->update($datos);
        }

        Schema::table('productos', function (Blueprint $table) {
            $table->dropUnique(['codigo']);
            $table->dropColumn(['codigo', 'tapas']);
        });
    }
};
