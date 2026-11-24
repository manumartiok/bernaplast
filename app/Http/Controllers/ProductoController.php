<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\GestionaArchivos;
use App\Models\Familia;
use App\Models\Producto;
use App\Support\ImportadorProductos;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    use GestionaArchivos;

    public function index(Request $request)
    {
        $q = trim((string) $request->input('q'));

        $items = Producto::with('familia')
            ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w
                ->where('nombre', 'like', "%{$q}%")
                ->orWhere('codigo', 'like', "%{$q}%")
                ->orWhere('tapas', 'like', "%{$q}%")))
            ->when($request->filled('familia_id'), fn ($query) => $query->where('familia_id', $request->input('familia_id')))
            ->orderBy('orden')
            ->get();

        return view('content.admin.producto', [
            'items' => $items,
            'familias' => Familia::orderBy('orden')->get(),
            'filtrando' => $q !== '' || $request->filled('familia_id'),
        ]);
    }

    public function create()
    {
        return view('content.admin.producto-creador', [
            'item' => new Producto(),
            'familias' => Familia::orderBy('orden')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $this->validar($request);

        $item = new Producto();
        $this->completar($item, $request);
        $item->save();

        return redirect()->route('adm.producto')
            ->with('success', 'Registro creado correctamente.');
    }

    public function show($id)
    {
        return view('content.admin.producto-editor', [
            'item' => Producto::findOrFail($id),
            'familias' => Familia::orderBy('orden')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validar($request);

        $item = Producto::findOrFail($id);
        $this->completar($item, $request);
        $item->save();

        return redirect()->route('adm.producto')
            ->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $item = Producto::findOrFail($id);
        $this->borrarArchivosDe($item, ['foto', 'apto_archivo'],['tapas' => Producto::SUBCAMPOS_TAPA]);
        $item->delete();

        return redirect()->route('adm.producto')
            ->with('success', 'Registro eliminado correctamente.');
    }

    public function switch($id)
    {
        $item = Producto::findOrFail($id);
        $item->active = !$item->active;
        $item->save();

        return redirect()->route('adm.producto')
            ->with('success', 'Estado actualizado correctamente.');
    }

    public function destacado($id)
    {
        $item = Producto::findOrFail($id);
        $item->destacado = !$item->destacado;
        $item->save();

        return redirect()->route('adm.producto')
            ->with('success', 'Destacado actualizado correctamente.');
    }

    /**
     * Importa productos (código, familia, nombre y tapas) desde el Excel de códigos (ver App\Support\ImportadorProductos).
     */
    public function importar(Request $request, ImportadorProductos $importador)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls|max:10240',
        ], [
            'archivo.required' => 'Elegí el archivo Excel de productos.',
            'archivo.mimes'    => 'El archivo tiene que ser un Excel (.xlsx o .xls).',
        ]);

        try {
            $resultado = $importador->importar($request->file('archivo')->getRealPath());
        } catch (\Throwable $e) {
            return redirect()->route('adm.producto')
                ->withErrors(['archivo' => 'No se pudo leer el Excel: ' . $e->getMessage()]);
        }

        return redirect()->route('adm.producto')
            ->with('importacion', $resultado)
            ->with('success', "Importación lista: {$resultado['creados']} productos creados y {$resultado['actualizados']} actualizados.");
    }

    public function reordenar(Request $request)
    {
        foreach ($request->orden as $fila) {
            Producto::where('id', $fila['id'])->update(['orden' => $fila['orden']]);
        }

        return response()->json(['success' => true]);
    }

    private function validar(Request $request): void
    {
        $request->validate([
            'foto' => 'nullable|image|max:2048',
            'apto_archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp,doc,docx|max:10240',
            'tapas.*.foto' => 'nullable|image|max:2048',
            'tapas.*.foto_descripcion' => 'nullable|image|max:2048',
        ]);
    }

    private function completar(Producto $item, Request $request): void
    {
        $item->familia_id = $request->input('familia_id') ?: null;
        $item->codigo = $request->input('codigo') ?: null;
        $item->orden = $request->input('orden');
        $item->nombre = $request->input('nombre');
        $item->foto = $this->procesarArchivo($request, 'foto', $item->foto, 'productos');
        $item->caracteristicas = $request->input('caracteristicas');
        $item->medidas = $request->input('medidas');
        $item->packaging = $request->input('packaging');
        $item->apto = array_key_exists((string) $request->input('apto'), Producto::APTOS) ? $request->input('apto') : null;
        $item->apto_archivo = $this->procesarArchivo($request, 'apto_archivo', $item->apto_archivo, 'productos');
        $item->nota = $request->input('nota');
        $item->tapas = $this->procesarLista($request, 'tapas', Producto::SUBCAMPOS_TAPA, $item->tapas, 'productos');
    }
}
