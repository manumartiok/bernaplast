<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\GestionaArchivos;
use App\Models\Desarrollo;
use Illuminate\Http\Request;

class DesarrolloController extends Controller
{
    use GestionaArchivos;

    public function show()
    {
        return view('content.admin.desarrollo', [
            'item' => Desarrollo::firstOrCreate([]),
        ]);
    }

    public function update(Request $request)
    {
        $this->validar($request);

        $item = Desarrollo::firstOrCreate([]);
        $this->completar($item, $request);
        $item->save();

        return redirect()->route('adm.desarrollo')
            ->with('success', 'Cambios guardados correctamente.');
    }

    private function validar(Request $request): void
    {
        $request->validate([
            'foto' => 'nullable|image|max:2048',
        ]);
    }

    private function completar(Desarrollo $item, Request $request): void
    {
        $item->foto = $this->procesarArchivo($request, 'foto', $item->foto, 'desarrollos');
        $item->texto = $request->input('texto');
        $item->pasos = $this->procesarLista($request, 'pasos', ['texto' => 'text'], $item->pasos, 'desarrollos');
        $item->texto2 = $request->input('texto2');
        $item->titulo_matricerias = $request->input('titulo_matricerias');
    }
}
