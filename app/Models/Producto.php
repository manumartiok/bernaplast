<?php

namespace App\Models;

use App\Models\Concerns\Traducible;
use App\Support\Idiomas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Producto extends Model
{
    use Traducible;

    protected $fillable = [
        'familia_id',
        'codigo',
        'orden',
        'nombre',
        'foto',
        'caracteristicas',
        'medidas',
        'packaging',
        'apto',
        'nota',
        'tapas',
        'active',
        'destacado',
    ];

    protected $casts = [
        'tapas' => 'array',
        'active' => 'boolean',
        'destacado' => 'boolean',
    ];

    // Campos que se guardan por idioma (según config/sitio.php)
    protected array $traducibles = [];

    // Subcampos de cada fila de tapas (para procesarLista / borrarArchivosDe)
    public const SUBCAMPOS_TAPA = ['grupo' => 'string', 'color' => 'string', 'codigo' => 'string', 'foto' => 'imagen', 'descripcion' => 'text'];

    // Valores posibles de "apto" y su texto
    public const APTOS = [
        'alimentos' => 'Apto alimentos',
        'quimicos' => 'Apto productos químicos',
    ];

    // Tipos de tapa que se eligen en el admin (el campo grupo de cada fila de tapas)
    public const GRUPOS_TAPA = [
        'TAPA a rosca con guarnición sin precinto',
        'TAPA a rosca sin guarnición con precinto',
    ];

    public function familia()
    {
        return $this->belongsTo(Familia::class);
    }

    /**
     * Tapas agrupadas por tipo, en el orden en que aparecen:
     * ['Tapa a rosca con guarnición sin precinto' => [fila, fila, ...], ...]
     */
    public function gruposDeTapas(): Collection
    {
        return collect($this->tapas ?? [])
            ->map(fn ($fila) => array_merge($fila, [
                'grupo' => Idiomas::texto($fila['grupo'] ?? '') ?: 'Tapa',
                'color' => Idiomas::texto($fila['color'] ?? ''),
                'descripcion' => Idiomas::texto($fila['descripcion'] ?? ''),
            ]))
            ->groupBy('grupo')
            ->map(fn ($filas) => $filas->values()->all());
    }

    /**
     * Nombre corto de un grupo para los selectores "Tapa":
     * "Tapa a rosca con guarnición sin precinto, sin manija" -> "Rosca c/guarn. s/prec., s/manija"
     */
    public static function grupoCorto(string $grupo): string
    {
        $corto = str_ireplace(
            ['Tapa a ', 'con guarnición', 'sin guarnición', 'con precinto', 'sin precinto', 'con manija', 'sin manija', 'del color de la tapa'],
            ['', 'c/guarn.', 's/guarn.', 'c/prec.', 's/prec.', 'c/manija', 's/manija', 'color tapa'],
            $grupo
        );

        return ucfirst(trim($corto));
    }

    /**
     * Código de conjunto de una tapa elegida (para el mail de presupuesto).
     */
    public function codigoDeTapa(?string $grupo, ?string $color): ?string
    {
        $fila = collect($this->gruposDeTapas()->get($grupo, []))->firstWhere('color', $color);

        return $fila['codigo'] ?? null;
    }
}
