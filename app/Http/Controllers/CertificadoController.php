<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\GestionaArchivos;
use App\Models\Certificado;
use Illuminate\Http\Request;

class CertificadoController extends Controller
{
    use GestionaArchivos;

    public function index()
    {
        $items = Certificado::orderBy('orden')->get();

        return view('content.admin.certificado', [
            'items' => $items,
        ]);
    }

    public function create()
    {
        return view('content.admin.certificado-creador', [
            'item' => new Certificado(),
        ]);
    }

    public function store(Request $request)
    {
        $this->validar($request);

        $item = new Certificado();
        $this->completar($item, $request);
        $item->save();

        return redirect()->route('adm.certificado')
            ->with('success', 'Registro creado correctamente.');
    }

    public function show($id)
    {
        return view('content.admin.certificado-editor', [
            'item' => Certificado::findOrFail($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validar($request);

        $item = Certificado::findOrFail($id);
        $this->completar($item, $request);
        $item->save();

        return redirect()->route('adm.certificado')
            ->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $item = Certificado::findOrFail($id);
        $this->borrarArchivosDe($item, ['foto', 'archivo'], []);
        $item->delete();

        return redirect()->route('adm.certificado')
            ->with('success', 'Registro eliminado correctamente.');
    }

    public function switch($id)
    {
        $item = Certificado::findOrFail($id);
        $item->active = !$item->active;
        $item->save();

        return redirect()->route('adm.certificado')
            ->with('success', 'Estado actualizado correctamente.');
    }

    public function reordenar(Request $request)
    {
        foreach ($request->orden as $fila) {
            Certificado::where('id', $fila['id'])->update(['orden' => $fila['orden']]);
        }

        return response()->json(['success' => true]);
    }

    private function validar(Request $request): void
    {
        $request->validate([
            'foto' => 'nullable|image|max:2048',
            'archivo' => 'nullable|file|max:10240',
        ]);
    }

    private function completar(Certificado $item, Request $request): void
    {
        $item->orden = $request->input('orden');
        $item->nombre = $request->input('nombre');
        $item->foto = $this->procesarArchivo($request, 'foto', $item->foto, 'certificados');
        $item->archivo = $this->procesarArchivo($request, 'archivo', $item->archivo, 'certificados');
    }
}
