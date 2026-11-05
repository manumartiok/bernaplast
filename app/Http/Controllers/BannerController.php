<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Un solo controlador para los banners de todas las secciones.
 * La sección llega por la URL: /admin/banner/nosotros, /admin/banner/contacto, ...
 */
class BannerController extends Controller
{
    public function show(string $seccion)
    {
        return view('content.admin.banner', [
            'banner' => Banner::seccion($seccion),
            'titulo' => Str::headline($seccion),
        ]);
    }

    public function update(Request $request, string $seccion)
    {
        $banner = Banner::seccion($seccion);
        $banner->texto = $request->input('texto');
        $banner->subtitulo = $request->input('subtitulo');
        $banner->save();

        return redirect()->route('adm.banner', $seccion)
            ->with('success', 'Banner actualizado correctamente.');
    }
}
