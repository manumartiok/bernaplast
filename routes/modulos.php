<?php

/*
|--------------------------------------------------------------------------
| Rutas de módulos generados
|--------------------------------------------------------------------------
|
| php artisan make:modulo agrega acá las rutas de cada módulo nuevo.
| Este archivo se carga desde routes/web.php, dentro del grupo AdminAuth.
|
*/

use Illuminate\Support\Facades\Route;

// Homes (make:modulo)
Route::get('/admin/home', [\App\Http\Controllers\HomeController::class, 'show'])->name('adm.home');
Route::post('/admin/home/update', [\App\Http\Controllers\HomeController::class, 'update'])->name('adm.home-update');

// Nosotros (make:modulo)
Route::get('/admin/nosotro', [\App\Http\Controllers\NosotroController::class, 'show'])->name('adm.nosotro');
Route::post('/admin/nosotro/update', [\App\Http\Controllers\NosotroController::class, 'update'])->name('adm.nosotro-update');

// Productos (make:modulo)
Route::get('/admin/producto', [\App\Http\Controllers\ProductoController::class, 'index'])->name('adm.producto');
Route::get('/admin/producto/creador', [\App\Http\Controllers\ProductoController::class, 'create'])->name('adm.producto-creador');
Route::post('/admin/producto/store', [\App\Http\Controllers\ProductoController::class, 'store'])->name('adm.producto-store');
Route::get('/admin/producto/editor/{id}', [\App\Http\Controllers\ProductoController::class, 'show'])->name('adm.producto-editor');
Route::post('/admin/producto/update/{id}', [\App\Http\Controllers\ProductoController::class, 'update'])->name('adm.producto-update');
Route::get('/admin/producto/destroy/{id}', [\App\Http\Controllers\ProductoController::class, 'destroy'])->name('adm.producto-destroy');
Route::get('/admin/producto/switch/{id}', [\App\Http\Controllers\ProductoController::class, 'switch'])->name('adm.producto-switch');
Route::get('/admin/producto/destacado/{id}', [\App\Http\Controllers\ProductoController::class, 'destacado'])->name('adm.producto-destacado');
Route::post('/admin/producto/reordenar', [\App\Http\Controllers\ProductoController::class, 'reordenar'])->name('adm.producto-reordenar');
Route::post('/admin/producto/importar', [\App\Http\Controllers\ProductoController::class, 'importar'])->name('adm.producto-importar');

// Categorias (make:modulo)
Route::get('/admin/categoria', [\App\Http\Controllers\CategoriaController::class, 'index'])->name('adm.categoria');
Route::get('/admin/categoria/creador', [\App\Http\Controllers\CategoriaController::class, 'create'])->name('adm.categoria-creador');
Route::post('/admin/categoria/store', [\App\Http\Controllers\CategoriaController::class, 'store'])->name('adm.categoria-store');
Route::get('/admin/categoria/editor/{id}', [\App\Http\Controllers\CategoriaController::class, 'show'])->name('adm.categoria-editor');
Route::post('/admin/categoria/update/{id}', [\App\Http\Controllers\CategoriaController::class, 'update'])->name('adm.categoria-update');
Route::get('/admin/categoria/destroy/{id}', [\App\Http\Controllers\CategoriaController::class, 'destroy'])->name('adm.categoria-destroy');
Route::get('/admin/categoria/switch/{id}', [\App\Http\Controllers\CategoriaController::class, 'switch'])->name('adm.categoria-switch');
Route::post('/admin/categoria/reordenar', [\App\Http\Controllers\CategoriaController::class, 'reordenar'])->name('adm.categoria-reordenar');

// Novedades (make:modulo)
Route::get('/admin/novedade', [\App\Http\Controllers\NovedadeController::class, 'index'])->name('adm.novedade');
Route::get('/admin/novedade/creador', [\App\Http\Controllers\NovedadeController::class, 'create'])->name('adm.novedade-creador');
Route::post('/admin/novedade/store', [\App\Http\Controllers\NovedadeController::class, 'store'])->name('adm.novedade-store');
Route::get('/admin/novedade/editor/{id}', [\App\Http\Controllers\NovedadeController::class, 'show'])->name('adm.novedade-editor');
Route::post('/admin/novedade/update/{id}', [\App\Http\Controllers\NovedadeController::class, 'update'])->name('adm.novedade-update');
Route::get('/admin/novedade/destroy/{id}', [\App\Http\Controllers\NovedadeController::class, 'destroy'])->name('adm.novedade-destroy');
Route::get('/admin/novedade/switch/{id}', [\App\Http\Controllers\NovedadeController::class, 'switch'])->name('adm.novedade-switch');
Route::get('/admin/novedade/destacado/{id}', [\App\Http\Controllers\NovedadeController::class, 'destacado'])->name('adm.novedade-destacado');
Route::post('/admin/novedade/reordenar', [\App\Http\Controllers\NovedadeController::class, 'reordenar'])->name('adm.novedade-reordenar');

// Desarrollos (make:modulo)
Route::get('/admin/desarrollo', [\App\Http\Controllers\DesarrolloController::class, 'show'])->name('adm.desarrollo');
Route::post('/admin/desarrollo/update', [\App\Http\Controllers\DesarrolloController::class, 'update'])->name('adm.desarrollo-update');

// Matricerias (make:modulo)
Route::get('/admin/matriceria', [\App\Http\Controllers\MatriceriaController::class, 'index'])->name('adm.matriceria');
Route::get('/admin/matriceria/creador', [\App\Http\Controllers\MatriceriaController::class, 'create'])->name('adm.matriceria-creador');
Route::post('/admin/matriceria/store', [\App\Http\Controllers\MatriceriaController::class, 'store'])->name('adm.matriceria-store');
Route::get('/admin/matriceria/editor/{id}', [\App\Http\Controllers\MatriceriaController::class, 'show'])->name('adm.matriceria-editor');
Route::post('/admin/matriceria/update/{id}', [\App\Http\Controllers\MatriceriaController::class, 'update'])->name('adm.matriceria-update');
Route::get('/admin/matriceria/destroy/{id}', [\App\Http\Controllers\MatriceriaController::class, 'destroy'])->name('adm.matriceria-destroy');
Route::get('/admin/matriceria/switch/{id}', [\App\Http\Controllers\MatriceriaController::class, 'switch'])->name('adm.matriceria-switch');
Route::post('/admin/matriceria/reordenar', [\App\Http\Controllers\MatriceriaController::class, 'reordenar'])->name('adm.matriceria-reordenar');

// Calidades (make:modulo)
Route::get('/admin/calidade', [\App\Http\Controllers\CalidadeController::class, 'show'])->name('adm.calidade');
Route::post('/admin/calidade/update', [\App\Http\Controllers\CalidadeController::class, 'update'])->name('adm.calidade-update');

// Certificados (make:modulo)
Route::get('/admin/certificado', [\App\Http\Controllers\CertificadoController::class, 'index'])->name('adm.certificado');
Route::get('/admin/certificado/creador', [\App\Http\Controllers\CertificadoController::class, 'create'])->name('adm.certificado-creador');
Route::post('/admin/certificado/store', [\App\Http\Controllers\CertificadoController::class, 'store'])->name('adm.certificado-store');
Route::get('/admin/certificado/editor/{id}', [\App\Http\Controllers\CertificadoController::class, 'show'])->name('adm.certificado-editor');
Route::post('/admin/certificado/update/{id}', [\App\Http\Controllers\CertificadoController::class, 'update'])->name('adm.certificado-update');
Route::get('/admin/certificado/destroy/{id}', [\App\Http\Controllers\CertificadoController::class, 'destroy'])->name('adm.certificado-destroy');
Route::get('/admin/certificado/switch/{id}', [\App\Http\Controllers\CertificadoController::class, 'switch'])->name('adm.certificado-switch');
Route::post('/admin/certificado/reordenar', [\App\Http\Controllers\CertificadoController::class, 'reordenar'])->name('adm.certificado-reordenar');

// Clientes (make:modulo)
Route::get('/admin/cliente', [\App\Http\Controllers\ClienteController::class, 'index'])->name('adm.cliente');
Route::get('/admin/cliente/creador', [\App\Http\Controllers\ClienteController::class, 'create'])->name('adm.cliente-creador');
Route::post('/admin/cliente/store', [\App\Http\Controllers\ClienteController::class, 'store'])->name('adm.cliente-store');
Route::get('/admin/cliente/editor/{id}', [\App\Http\Controllers\ClienteController::class, 'show'])->name('adm.cliente-editor');
Route::post('/admin/cliente/update/{id}', [\App\Http\Controllers\ClienteController::class, 'update'])->name('adm.cliente-update');
Route::get('/admin/cliente/destroy/{id}', [\App\Http\Controllers\ClienteController::class, 'destroy'])->name('adm.cliente-destroy');
Route::get('/admin/cliente/switch/{id}', [\App\Http\Controllers\ClienteController::class, 'switch'])->name('adm.cliente-switch');
Route::post('/admin/cliente/reordenar', [\App\Http\Controllers\ClienteController::class, 'reordenar'])->name('adm.cliente-reordenar');

// Familias (make:modulo)
Route::get('/admin/familia', [\App\Http\Controllers\FamiliaController::class, 'index'])->name('adm.familia');
Route::get('/admin/familia/creador', [\App\Http\Controllers\FamiliaController::class, 'create'])->name('adm.familia-creador');
Route::post('/admin/familia/store', [\App\Http\Controllers\FamiliaController::class, 'store'])->name('adm.familia-store');
Route::get('/admin/familia/editor/{id}', [\App\Http\Controllers\FamiliaController::class, 'show'])->name('adm.familia-editor');
Route::post('/admin/familia/update/{id}', [\App\Http\Controllers\FamiliaController::class, 'update'])->name('adm.familia-update');
Route::get('/admin/familia/destroy/{id}', [\App\Http\Controllers\FamiliaController::class, 'destroy'])->name('adm.familia-destroy');
Route::get('/admin/familia/switch/{id}', [\App\Http\Controllers\FamiliaController::class, 'switch'])->name('adm.familia-switch');
Route::post('/admin/familia/reordenar', [\App\Http\Controllers\FamiliaController::class, 'reordenar'])->name('adm.familia-reordenar');
