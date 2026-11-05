<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\GestionaArchivos;
use App\Models\Nosotro;
use Illuminate\Http\Request;

class NosotroController extends Controller
{
    use GestionaArchivos;

    public function show()
    {
        return view('content.admin.nosotro', [
            'item' => Nosotro::firstOrCreate([]),
        ]);
    }

    public function update(Request $request)
    {
        $this->validar($request);

        $item = Nosotro::firstOrCreate([]);
        $this->completar($item, $request);
        $item->save();

        return redirect()->route('adm.nosotro')
            ->with('success', 'Cambios guardados correctamente.');
    }

    private function validar(Request $request): void
    {
        $request->validate([
            'foto' => 'nullable|image|max:2048',
        ]);
    }

    private function completar(Nosotro $item, Request $request): void
    {
        $item->foto = $this->procesarArchivo($request, 'foto', $item->foto, 'nosotros');
        $item->titulo = $request->input('titulo');
        $item->texto = $request->input('texto');
        $item->titulo_valores = $request->input('titulo_valores');
        $item->valores = $this->procesarLista($request, 'valores', ['titulo' => 'string', 'texto' => 'text'], $item->valores, 'nosotros');
    }
}
