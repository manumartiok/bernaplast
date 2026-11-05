<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\GestionaArchivos;
use App\Models\Novedade;
use App\Models\Categoria;
use Illuminate\Http\Request;

class NovedadeController extends Controller
{
    use GestionaArchivos;

    public function index()
    {
        $items = Novedade::orderBy('orden')->get();

        return view('content.admin.novedade', [
            'items' => $items,
        ]);
    }

    public function create()
    {
        return view('content.admin.novedade-creador', [
            'item' => new Novedade(),
            'categorias' => Categoria::all(),
        ]);
    }

    public function store(Request $request)
    {
        $this->validar($request);

        $item = new Novedade();
        $this->completar($item, $request);
        $item->save();

        return redirect()->route('adm.novedade')
            ->with('success', 'Registro creado correctamente.');
    }

    public function show($id)
    {
        return view('content.admin.novedade-editor', [
            'item' => Novedade::findOrFail($id),
            'categorias' => Categoria::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validar($request);

        $item = Novedade::findOrFail($id);
        $this->completar($item, $request);
        $item->save();

        return redirect()->route('adm.novedade')
            ->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $item = Novedade::findOrFail($id);
        $this->borrarArchivosDe($item, ['foto'], []);
        $item->delete();

        return redirect()->route('adm.novedade')
            ->with('success', 'Registro eliminado correctamente.');
    }

    public function switch($id)
    {
        $item = Novedade::findOrFail($id);
        $item->active = !$item->active;
        $item->save();

        return redirect()->route('adm.novedade')
            ->with('success', 'Estado actualizado correctamente.');
    }

    public function destacado($id)
    {
        $item = Novedade::findOrFail($id);
        $item->destacado = !$item->destacado;
        $item->save();

        return redirect()->route('adm.novedade')
            ->with('success', 'Destacado actualizado correctamente.');
    }

    public function reordenar(Request $request)
    {
        foreach ($request->orden as $fila) {
            Novedade::where('id', $fila['id'])->update(['orden' => $fila['orden']]);
        }

        return response()->json(['success' => true]);
    }

    private function validar(Request $request): void
    {
        $request->validate([
            'foto' => 'nullable|image|max:2048',
        ]);
    }

    private function completar(Novedade $item, Request $request): void
    {
        $item->categoria_id = $request->input('categoria_id') ?: null;
        $item->orden = $request->input('orden');
        $item->fecha = $request->filled('fecha') ? $request->input('fecha') : null;
        $item->titulo = $request->input('titulo');
        $item->foto = $this->procesarArchivo($request, 'foto', $item->foto, 'novedades');
        $item->texto = $request->input('texto');
    }
}
