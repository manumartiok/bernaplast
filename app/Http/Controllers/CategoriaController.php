<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\GestionaArchivos;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    use GestionaArchivos;

    public function index()
    {
        $items = Categoria::orderBy('orden')->get();

        return view('content.admin.categoria', [
            'items' => $items,
        ]);
    }

    public function create()
    {
        return view('content.admin.categoria-creador', [
            'item' => new Categoria(),
        ]);
    }

    public function store(Request $request)
    {
        $item = new Categoria();
        $this->completar($item, $request);
        $item->save();

        return redirect()->route('adm.categoria')
            ->with('success', 'Registro creado correctamente.');
    }

    public function show($id)
    {
        return view('content.admin.categoria-editor', [
            'item' => Categoria::findOrFail($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = Categoria::findOrFail($id);
        $this->completar($item, $request);
        $item->save();

        return redirect()->route('adm.categoria')
            ->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $item = Categoria::findOrFail($id);
        $this->borrarArchivosDe($item, [], []);
        $item->delete();

        return redirect()->route('adm.categoria')
            ->with('success', 'Registro eliminado correctamente.');
    }

    public function switch($id)
    {
        $item = Categoria::findOrFail($id);
        $item->active = !$item->active;
        $item->save();

        return redirect()->route('adm.categoria')
            ->with('success', 'Estado actualizado correctamente.');
    }

    public function reordenar(Request $request)
    {
        foreach ($request->orden as $fila) {
            Categoria::where('id', $fila['id'])->update(['orden' => $fila['orden']]);
        }

        return response()->json(['success' => true]);
    }

    private function completar(Categoria $item, Request $request): void
    {
        $item->orden = $request->input('orden');
        $item->nombre = $request->input('nombre');
    }
}
