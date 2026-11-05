<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\GestionaArchivos;
use App\Models\Matriceria;
use Illuminate\Http\Request;

class MatriceriaController extends Controller
{
    use GestionaArchivos;

    public function index()
    {
        $items = Matriceria::orderBy('orden')->get();

        return view('content.admin.matriceria', [
            'items' => $items,
        ]);
    }

    public function create()
    {
        return view('content.admin.matriceria-creador', [
            'item' => new Matriceria(),
        ]);
    }

    public function store(Request $request)
    {
        $this->validar($request);

        $item = new Matriceria();
        $this->completar($item, $request);
        $item->save();

        return redirect()->route('adm.matriceria')
            ->with('success', 'Registro creado correctamente.');
    }

    public function show($id)
    {
        return view('content.admin.matriceria-editor', [
            'item' => Matriceria::findOrFail($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validar($request);

        $item = Matriceria::findOrFail($id);
        $this->completar($item, $request);
        $item->save();

        return redirect()->route('adm.matriceria')
            ->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $item = Matriceria::findOrFail($id);
        $this->borrarArchivosDe($item, ['foto'], []);
        $item->delete();

        return redirect()->route('adm.matriceria')
            ->with('success', 'Registro eliminado correctamente.');
    }

    public function switch($id)
    {
        $item = Matriceria::findOrFail($id);
        $item->active = !$item->active;
        $item->save();

        return redirect()->route('adm.matriceria')
            ->with('success', 'Estado actualizado correctamente.');
    }

    public function reordenar(Request $request)
    {
        foreach ($request->orden as $fila) {
            Matriceria::where('id', $fila['id'])->update(['orden' => $fila['orden']]);
        }

        return response()->json(['success' => true]);
    }

    private function validar(Request $request): void
    {
        $request->validate([
            'foto' => 'nullable|image|max:2048',
        ]);
    }

    private function completar(Matriceria $item, Request $request): void
    {
        $item->orden = $request->input('orden');
        $item->nombre = $request->input('nombre');
        $item->foto = $this->procesarArchivo($request, 'foto', $item->foto, 'matricerias');
    }
}
