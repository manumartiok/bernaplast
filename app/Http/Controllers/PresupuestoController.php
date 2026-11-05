<?php

namespace App\Http\Controllers;

use App\Mail\PresupuestoMail;
use App\Models\Contacto;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Solicitud de presupuesto pública: los productos elegidos se guardan en la sesión.
 * session('presupuesto') = [id => ['producto_id', 'tapa' (grupo de la tapa), 'color', 'cantidad']]
 */
class PresupuestoController extends Controller
{
    public function agregar(Request $request)
    {
        $datos = $this->validarItem($request, true);
        $items = session('presupuesto', []);

        // Si ya está la misma combinación, suma la cantidad
        foreach ($items as $id => $item) {
            if ($item['producto_id'] == $datos['producto_id'] && $item['tapa'] === $datos['tapa'] && $item['color'] === $datos['color']) {
                $items[$id]['cantidad'] += $datos['cantidad'];
                session(['presupuesto' => $items]);

                return $this->volver();
            }
        }

        $items[(string) Str::uuid()] = $datos;
        session(['presupuesto' => $items]);

        return $this->volver();
    }

    public function actualizar(Request $request, string $id)
    {
        $items = session('presupuesto', []);
        abort_unless(isset($items[$id]), 404);

        $items[$id] = array_merge($items[$id], $this->validarItem($request, false));
        session(['presupuesto' => $items]);

        return response()->json(['success' => true]);
    }

    public function quitar(string $id)
    {
        $items = session('presupuesto', []);
        unset($items[$id]);
        session(['presupuesto' => $items]);

        return response()->json(['success' => true, 'quedan' => count($items)]);
    }

    public function enviar(Request $request)
    {
        $datos = $request->validate([
            'nombre'   => 'required|string|max:255',
            'telefono' => 'required|string|max:100',
            'email'    => 'required|email|max:255',
            'archivo'  => 'nullable|file|max:10240',
        ], [
            'nombre.required'   => 'Ingresá tu nombre y apellido.',
            'telefono.required' => 'Ingresá tu teléfono.',
            'email.required'    => 'Ingresá tu email.',
            'email.email'       => 'El email no es válido.',
            'archivo.max'       => 'El archivo no puede superar los 10MB.',
        ]);

        $items = $this->itemsConProducto(session('presupuesto', []));

        if (empty($items)) {
            return back()->withErrors(['presupuesto' => 'Agregá al menos un producto a la solicitud.']);
        }

        $destino = Contacto::first()?->mail ?: config('mail.from.address');

        try {
            Mail::to($destino)->send(new PresupuestoMail($datos, $items, $request->file('archivo')));
        } catch (\Exception $e) {
            Log::error('Error al enviar la solicitud de presupuesto: ' . $e->getMessage());

            return back()->withInput()->withErrors(['presupuesto' => 'No pudimos enviar la solicitud. Intentá nuevamente.']);
        }

        session()->forget('presupuesto');

        return redirect()->route('presupuesto')
            ->with('success', 'Recibimos tu solicitud. Te vamos a contactar a la brevedad.');
    }

    /**
     * Ítems de la sesión con su producto cargado (descarta los productos que ya no existen).
     */
    public static function itemsConProducto(array $items): array
    {
        $productos = Producto::whereIn('id', array_column($items, 'producto_id'))->get()->keyBy('id');

        $resultado = [];
        foreach ($items as $id => $item) {
            if ($producto = $productos->get($item['producto_id'])) {
                $resultado[$id] = $item + ['producto' => $producto];
            }
        }

        return $resultado;
    }

    private function validarItem(Request $request, bool $conProducto): array
    {
        $reglas = [
            'tapa'     => 'nullable|string|max:255',
            'color'    => 'nullable|string|max:100',
            'cantidad' => 'required|integer|min:1',
        ];
        if ($conProducto) {
            $reglas['producto_id'] = 'required|integer|exists:productos,id';
        }

        $datos = $request->validate($reglas);

        return array_filter([
            'producto_id' => isset($datos['producto_id']) ? (int) $datos['producto_id'] : null,
            'tapa'        => $datos['tapa'] ?? null,
            'color'       => $datos['color'] ?? null,
            'cantidad'    => (int) $datos['cantidad'],
        ], fn ($v, $k) => $k !== 'producto_id' || $v !== null, ARRAY_FILTER_USE_BOTH);
    }

    private function volver()
    {
        return redirect()->route('presupuesto')
            ->with('success', 'Producto agregado a tu solicitud de presupuesto.');
    }
}
