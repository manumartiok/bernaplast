<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\GestionaArchivos;
use App\Models\Calidade;
use Illuminate\Http\Request;

class CalidadeController extends Controller
{
    use GestionaArchivos;

    public function show()
    {
        return view('content.admin.calidade', [
            'item' => Calidade::firstOrCreate([]),
        ]);
    }

    public function update(Request $request)
    {
        $this->validar($request);

        $item = Calidade::firstOrCreate([]);
        $this->completar($item, $request);
        $item->save();

        return redirect()->route('adm.calidade')
            ->with('success', 'Cambios guardados correctamente.');
    }

    private function validar(Request $request): void
    {
        $request->validate([
            'foto' => 'nullable|image|max:2048',
            'archivo' => 'nullable|file|max:10240',
        ]);
    }

    private function completar(Calidade $item, Request $request): void
    {
        $item->texto = $request->input('texto');
        $item->pilares = $this->procesarLista($request, 'pilares', ['titulo' => 'string', 'texto' => 'text'], $item->pilares, 'calidades');
        $item->foto = $this->procesarArchivo($request, 'foto', $item->foto, 'calidades');
        $item->archivo = $this->procesarArchivo($request, 'archivo', $item->archivo, 'calidades');
        $item->titulo_certificados = $request->input('titulo_certificados');
    }
}
