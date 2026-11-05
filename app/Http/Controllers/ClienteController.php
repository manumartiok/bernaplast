<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\GestionaArchivos;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    use GestionaArchivos;

    public function index()
    {
        $items = Cliente::orderBy('orden')->get();

        return view('content.admin.cliente', [
            'items' => $items,
        ]);
    }

    public function create()
    {
        return view('content.admin.cliente-creador', [
            'item' => new Cliente(),
        ]);
    }

    public function store(Request $request)
    {
        $this->validar($request);

        $item = new Cliente();
        $this->completar($item, $request);
        $item->save();

        return redirect()->route('adm.cliente')
            ->with('success', 'Registro creado correctamente.');
    }

    public function show($id)
    {
        return view('content.admin.cliente-editor', [
            'item' => Cliente::findOrFail($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validar($request);

        $item = Cliente::findOrFail($id);
        $this->completar($item, $request);
        $item->save();

        return redirect()->route('adm.cliente')
            ->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $item = Cliente::findOrFail($id);
        $this->borrarArchivosDe($item, ['foto'], []);
        $item->delete();

        return redirect()->route('adm.cliente')
            ->with('success', 'Registro eliminado correctamente.');
    }

    public function switch($id)
    {
        $item = Cliente::findOrFail($id);
        $item->active = !$item->active;
        $item->save();

        return redirect()->route('adm.cliente')
            ->with('success', 'Estado actualizado correctamente.');
    }

    public function reordenar(Request $request)
    {
        foreach ($request->orden as $fila) {
            Cliente::where('id', $fila['id'])->update(['orden' => $fila['orden']]);
        }

        return response()->json(['success' => true]);
    }

    private function validar(Request $request): void
    {
        $request->validate([
            'foto' => 'nullable|image|max:2048',
        ]);
    }

    private function completar(Cliente $item, Request $request): void
    {
        $item->orden = $request->input('orden');
        $item->nombre = $request->input('nombre');
        $item->foto = $this->procesarArchivo($request, 'foto', $item->foto, 'clientes');
    }
}
