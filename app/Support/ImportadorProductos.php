<?php

namespace App\Support;

use App\Models\Familia;
use App\Models\Producto;
use Illuminate\Support\Str;
use InvalidArgumentException;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Importa los productos desde el Excel de códigos. Se usa desde el listado de productos del admin
 * (ProductoController@importar). Solo se leen las hojas Conjunto y Tapas.
 *
 * Criterio:
 * - Cada fila de la hoja Conjunto es un producto. La referencia es la columna Código: si ya hay un producto
 *   con ese código se actualiza, si no se crea.
 * - Familia (columna A, que viene combinada por grupo) -> familia_id. Si la familia no existe se crea.
 * - Descripción -> nombre del producto.
 * - El código de tapa se busca en la hoja Tapas:
 *     Guarni. = si   -> fila "TAPA a {tipo} con guarnición sin precinto"
 *     Presinto = si  -> fila "TAPA a {tipo} sin guarnición con precinto"
 *     las dos en si  -> se crean las dos filas
 *     ninguna        -> fila "TAPA a {tipo} sin guarnición sin precinto"
 *   El color de cada fila es la columna Color de la hoja Tapas.
 * - El resto de los campos (foto, características, medidas, packaging, apto, nota, destacado) se cargan a mano
 *   y la importación no los toca. De las tapas ya cargadas se conservan la foto y la descripción.
 */
class ImportadorProductos
{
    private array $avisos = [];
    private array $familiasNuevas = [];

    /**
     * @return array ['avisos' => [...], 'creados' => n, 'actualizados' => n, 'familias_nuevas' => [...]]
     */
    public function importar(string $archivo): array
    {
        $hojas = $this->leerHojas($archivo);
        foreach (['conjunto', 'tapas'] as $necesaria) {
            if (!isset($hojas[$necesaria])) {
                throw new InvalidArgumentException('Falta la hoja "' . Str::ucfirst($necesaria) . '" en el Excel.');
            }
        }

        $productos = $this->armarProductos($hojas['conjunto'], $this->indexar($hojas['tapas']));

        [$creados, $actualizados] = $this->guardar($productos);

        return [
            'avisos'          => $this->avisos,
            'creados'         => $creados,
            'actualizados'    => $actualizados,
            'familias_nuevas' => $this->familiasNuevas,
        ];
    }

    // ------------------------------------------------------------------
    // Lectura del Excel
    // ------------------------------------------------------------------

    /** Hojas como listas de filas [A => valor, B => valor, ...], sin filas vacías. */
    private function leerHojas(string $archivo): array
    {
        $hojas = [];
        foreach (IOFactory::load($archivo)->getWorksheetIterator() as $hoja) {
            $filas = [];
            foreach ($hoja->toArray(null, true, true, true) as $fila) {
                $fila = array_map(fn ($v) => is_string($v) ? trim($v) : $v, $fila);
                if (array_filter($fila, fn ($v) => $v !== null && $v !== '')) {
                    $filas[] = $fila;
                }
            }
            $hojas[$this->normalizar($hoja->getTitle())] = $filas;
        }

        return $hojas;
    }

    /** Filas de una hoja indexadas por el código de la columna A (salta el encabezado). */
    private function indexar(array $filas): array
    {
        $resultado = [];
        foreach ($filas as $fila) {
            $codigo = (string) ($fila['A'] ?? '');
            if ($codigo !== '' && !$this->esEncabezado($codigo)) {
                $resultado[$codigo] = $fila;
            }
        }

        return $resultado;
    }

    private function esEncabezado(string $valor): bool
    {
        return $this->normalizar($valor) === 'codigo';
    }

    // ------------------------------------------------------------------
    // Armado de productos
    // ------------------------------------------------------------------

    private function armarProductos(array $conjunto, array $tapas): array
    {
        $productos = [];
        $familia = null;

        foreach ($conjunto as $fila) {
            // La familia viene en celdas combinadas: solo la primera fila del grupo la tiene
            if (!empty($fila['A']) && $this->normalizar((string) $fila['A']) !== 'familia') {
                $familia = (string) $fila['A'];
            }

            $codigo = (string) ($fila['B'] ?? '');
            if ($codigo === '' || $this->esEncabezado($codigo)) {
                continue;
            }
            $nombre = (string) ($fila['C'] ?? '');

            if (isset($productos[$codigo])) {
                $this->avisar("Conjunto {$codigo}: el código está repetido (\"{$productos[$codigo]['nombre']}\" y \"{$nombre}\"); se importa solo la primera fila.");
                continue;
            }
            if ($nombre === '') {
                $this->avisar("Conjunto {$codigo}: no tiene descripción; el producto queda sin nombre.");
            }

            $productos[$codigo] = [
                'codigo'  => $codigo,
                'nombre'  => $nombre,
                'familia' => $familia,
                'tapas'   => $this->tapasDe($codigo, $fila['E'] ?? null, $tapas),
            ];
        }

        return $productos;
    }

    /**
     * Filas de tapas del producto a partir del código de tapa, o null si la fila no tiene tapa
     * (en ese caso no se tocan las tapas que el producto ya tenga cargadas).
     */
    private function tapasDe(string $producto, $codigoTapa, array $tapas): ?array
    {
        $codigoTapa = trim((string) $codigoTapa);
        if ($codigoTapa === '') {
            $this->avisar("Conjunto {$producto}: no tiene código de tapa; las tapas se cargan a mano.");
            return null;
        }

        $tapa = $tapas[$codigoTapa] ?? null;
        if (!$tapa) {
            $this->avisar("Conjunto {$producto}: la tapa {$codigoTapa} no está en la hoja Tapas; las tapas se cargan a mano.");
            return null;
        }

        $tipo = Str::lower((string) ($tapa['E'] ?? '')) ?: 'rosca';
        $color = Str::ucfirst(Str::lower((string) ($tapa['F'] ?? '')));
        $guarnicion = $this->esSi($tapa['G'] ?? null);
        $precinto = $this->esSi($tapa['H'] ?? null);

        if ($color === '') {
            $this->avisar("Tapa {$codigoTapa}: no tiene color.");
        }

        $grupos = [];
        if ($guarnicion) {
            $grupos[] = "TAPA a {$tipo} con guarnición sin precinto";
        }
        if ($precinto) {
            $grupos[] = "TAPA a {$tipo} sin guarnición con precinto";
        }
        if (!$grupos) {
            $grupos[] = "TAPA a {$tipo} sin guarnición sin precinto";
        }

        return array_map(fn ($grupo) => [
            'grupo'       => $grupo,
            'color'       => $color,
            'codigo'      => $codigoTapa,
            'foto'        => null,
            'descripcion' => null,
        ], $grupos);
    }

    private function esSi($valor): bool
    {
        return in_array($this->normalizar((string) $valor), ['si', 's', 'x', '1'], true);
    }

    // ------------------------------------------------------------------
    // Guardado
    // ------------------------------------------------------------------

    private function guardar(array $productos): array
    {
        $creados = $actualizados = 0;
        $orden = Producto::max('orden') ?: 'A@';

        foreach ($productos as $datos) {
            $producto = Producto::firstWhere('codigo', $datos['codigo']);

            if (!$producto) {
                $orden = $this->siguienteOrden($orden);
                $producto = new Producto(['codigo' => $datos['codigo'], 'orden' => $orden]);
                $creados++;
            } else {
                $actualizados++;
            }

            $producto->familia_id = $this->familia($datos['familia'])?->id;
            $producto->nombre = $datos['nombre'];
            if ($datos['tapas'] !== null) {
                $producto->tapas = $this->conservarFotos($datos['tapas'], $producto->tapas ?? []);
            }
            $producto->save();
        }

        return [$creados, $actualizados];
    }

    /** Mantiene la foto y la descripción de las tapas que ya estaban cargadas (mismo grupo y color). */
    private function conservarFotos(array $nuevas, array $anteriores): array
    {
        $clave = fn ($t) => mb_strtolower(($t['grupo'] ?? '') . '|' . ($t['color'] ?? ''));

        foreach ($nuevas as &$tapa) {
            $anterior = collect($anteriores)->first(fn ($a) => $clave($a) === $clave($tapa));
            $tapa['foto'] = $anterior['foto'] ?? null;
            $tapa['descripcion'] = $anterior['descripcion'] ?? null;
        }

        return $nuevas;
    }

    /** Busca la familia ignorando puntos y espacios ("3000 c.c." = "3000 cc"); si no existe la crea. */
    private function familia(?string $nombre): ?Familia
    {
        if (!$nombre) {
            return null;
        }
        $clave = fn ($n) => Str::lower(str_replace(['.', ' '], '', Str::ascii((string) Idiomas::texto($n))));
        $familia = Familia::all()->first(fn ($f) => $clave($f->nombre) === $clave($nombre));

        if (!$familia) {
            $limpio = trim(str_ireplace('c.c.', 'cc', $nombre));
            $familia = Familia::create(['nombre' => $limpio, 'orden' => $this->siguienteOrden(Familia::max('orden') ?: 'A@')]);
            $this->familiasNuevas[] = $limpio;
        }

        return $familia;
    }

    /** Orden de dos letras como el drag & drop del admin: AA, AB, ... AZ, BA */
    private function siguienteOrden(string $orden): string
    {
        $orden = str_pad(Str::upper($orden), 2, 'A', STR_PAD_LEFT);
        [$a, $b] = [$orden[0], $orden[1]];
        if ($b < 'Z') {
            return $a . chr(ord($b) + 1);
        }

        return chr(ord($a) + 1) . 'A';
    }

    // ------------------------------------------------------------------
    // Utilidades
    // ------------------------------------------------------------------

    private function normalizar(string $texto): string
    {
        return Str::lower(Str::ascii(trim($texto)));
    }

    private function avisar(string $mensaje): void
    {
        $this->avisos[] = $mensaje;
    }
}
