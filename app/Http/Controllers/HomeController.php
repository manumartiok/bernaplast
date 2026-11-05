<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\GestionaArchivos;
use App\Models\Home;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use GestionaArchivos;

    public function show()
    {
        return view('content.admin.home', [
            'item' => Home::firstOrCreate([]),
        ]);
    }

    public function update(Request $request)
    {
        $this->validar($request);

        $item = Home::firstOrCreate([]);
        $this->completar($item, $request);
        $item->save();

        return redirect()->route('adm.home')
            ->with('success', 'Cambios guardados correctamente.');
    }

    private function validar(Request $request): void
    {
        $request->validate([
            'foto_nosotros' => 'nullable|image|max:2048',
        ]);
    }

    private function completar(Home $item, Request $request): void
    {
        $item->titulo_productos = $request->input('titulo_productos');
        $item->texto_productos = $request->input('texto_productos');
        $item->foto_nosotros = $this->procesarArchivo($request, 'foto_nosotros', $item->foto_nosotros, 'homes');
        $item->titulo_nosotros = $request->input('titulo_nosotros');
        $item->texto_nosotros = $request->input('texto_nosotros');
        $item->titulo_novedades = $request->input('titulo_novedades');
        $item->texto_novedades = $request->input('texto_novedades');
        $item->titulo_clientes = $request->input('titulo_clientes');
        $item->texto_clientes = $request->input('texto_clientes');
    }
}
