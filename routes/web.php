<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\AdminAuth;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\PerfilController;
use App\Http\Controllers\HomeBannerController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ContactoController;

use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\LogoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\MetadatoController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\PresupuestoController;

//ADMIN
Route::middleware(AdminAuth::class)->group(function () {

    //ADMIN
    Route::get('/admin/dashboard', function () {
    return view('content.admin.admin-dashboard');})->name('adm.dashboard');

    //perfil
    Route::get('/admin/perfil', [PerfilController::class, 'show'])->name('adm.perfil');
    Route::post('/admin/perfil/update', [PerfilController::class, 'update'])->name('adm.perfil-update');

    //home banner
    Route::get('/admin/home/banner', [HomeBannerController::class,'index'])->name('adm.home-ban');
    Route::get('/admin/home/banner/creador', [HomeBannerController::class,'create'])->name('adm.home-ban-creador');
    Route::post('/admin/home/banner/store', [HomeBannerController::class,'store'])->name('adm.home-ban-store');
    Route::get('/admin/home/banner/editor/{home_banners_id}', [HomeBannerController::class,'show'])->name('adm.home-ban-editor');
    Route::post('/admin/home/banner/update', [HomeBannerController::class,'update'])->name('adm.home-ban-update');
    Route::get('/admin/home/banner/destroy/{home_banners_id}', [HomeBannerController::class,'destroy'])->name('adm.home-ban-destroy');
    Route::get('/admin/home/banner/switch/{home_banners_id}', [HomeBannerController::class,'switch'])->name('adm.home-ban-switch');
    Route::post('/admin/home-ban/reordenar', [HomeBannerController::class, 'reordenar'])->name('adm.home-ban-reordenar');

    // contacto
    Route::get('/admin/contacto', [ContactoController::class, 'show'])->name('adm.contacto');
    Route::post('/admin/contacto/update', [ContactoController::class, 'update'])->name('adm.contacto.update');

    //newsletter
    Route::get('/admin/newsletter', [NewsletterController::class,'index'])->name('adm.newsletter');

    Route::post('/admin/newsletter/send', [NewsletterController::class,'send'])->name('adm.newsletter-send'); // NUEVA RUTA
    Route::get('/admin/newsletter/destroy/{newsletter_id}', [NewsletterController::class,'destroy'])->name('adm.newsletter-destroy');
    Route::get('/admin/newsletter/switch/{newsletter_id}', [NewsletterController::class,'switch'])->name('adm.newsletter-switch');
    Route::post('/admin/newsletter/reordenar', [NewsletterController::class, 'reordenar'])->name('adm.newsletter-reordenar');

    //logo
    Route::get('/admin/logo', [LogoController::class, 'show'])->name('adm.logo');
    Route::post('/admin/logo/update', [LogoController::class,'update'])->name('adm.logo-update');

    //usuarios
    Route::get('/admin/usuarios', [UsuarioController::class,'index'])->name('adm.usuarios');
    Route::get('/admin/usuarios/creador', [UsuarioController::class,'create'])->name('adm.usuarios-creador');
    Route::post('/admin/usuarios/store', [UsuarioController::class,'store'])->name('adm.usuarios-store');
    Route::get('/admin/usuarios/editor/{usuario_id}', [UsuarioController::class,'show'])->name('adm.usuarios-editor');
    Route::post('/admin/usuarios/update', [UsuarioController::class,'update'])->name('adm.usuarios-update');
    Route::get('/admin/usuarios/destroy/{usuario_id}', [UsuarioController::class,'destroy'])->name('adm.usuarios-destroy');
    Route::get('/admin/usuarios/switch/{usuario_id}', [UsuarioController::class,'switch'])->name('adm.usuarios-switch');

    //metadatos
    Route::get('/admin/metadatos', [MetadatoController::class,'index'])->name('adm.metadatos');
    Route::get('/admin/metadatos/creador', [MetadatoController::class,'create'])->name('adm.metadatos-creador');
    Route::post('/admin/metadatos/store', [MetadatoController::class,'store'])->name('adm.metadatos-store');
    Route::get('/admin/metadatos/editor/{metadato_id}', [MetadatoController::class,'show'])->name('adm.metadatos-editor');
    Route::post('/admin/metadatos/update', [MetadatoController::class,'update'])->name('adm.metadatos-update');
    Route::get('/admin/metadatos/destroy/{metadato_id}', [MetadatoController::class,'destroy'])->name('adm.metadatos-destroy');

    //banners (uno por sección: nosotros, productos, desarrollo, calidad, clientes, novedades, contacto, presupuesto)
    Route::get('/admin/banner/{seccion}', [BannerController::class, 'show'])->name('adm.banner');
    Route::post('/admin/banner/{seccion}', [BannerController::class, 'update'])->name('adm.banner-update');

    //módulos generados con php artisan make:modulo
    require __DIR__.'/modulos.php';
});

//idioma
Route::get('/idioma/{idioma}', function ($idioma) {
    if (in_array($idioma, \App\Support\Idiomas::todos())) {
        session(['idioma' => $idioma]);
    }
    return back();
})->name('idioma.cambiar');


//login
Route::get('/admin/login', [AuthController::class,'showLogin'])->name('login');
Route::post('/admin/login', [AuthController::class,'login'])->name('login.post');
Route::post('/admin/logout', [AuthController::class,'logout'])->name('logout');
Route::post('/admin/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');


Route::post('/admin/newsletter/store', [NewsletterController::class,'store'])->name('adm.newsletter-store');

//WEB
Route::get('/', function () {
    return view('content.web.home');})->name('home');

Route::get('/nosotros', function () {
    return view('content.web.nosotros');})->name('nosotros');

Route::get('/productos', function () {
    return view('content.web.productos');})->name('productos');

Route::get('/productos/{id}', function ($id) {
    return view('content.web.productos-detalle', ['id' => $id]);})->name('productos.detalle');

Route::get('/desarrollo-de-producto', function () {
    return view('content.web.desarrollo');})->name('desarrollo');

Route::get('/calidad', function () {
    return view('content.web.calidad');})->name('calidad');

Route::get('/clientes', function () {
    return view('content.web.clientes');})->name('clientes');

Route::get('/novedades', function () {
    return view('content.web.novedades');})->name('novedades');

Route::get('/novedades/{id}', function ($id) {
    return view('content.web.novedades-detalle', ['id' => $id]);})->name('novedades.detalle');

Route::get('/presupuesto', function () {
    return view('content.web.presupuesto');})->name('presupuesto');

Route::post('/presupuesto/agregar', [PresupuestoController::class, 'agregar'])->name('presupuesto.agregar');
Route::post('/presupuesto/actualizar/{id}', [PresupuestoController::class, 'actualizar'])->name('presupuesto.actualizar');
Route::post('/presupuesto/quitar/{id}', [PresupuestoController::class, 'quitar'])->name('presupuesto.quitar');
Route::post('/presupuesto/enviar', [PresupuestoController::class, 'enviar'])->name('presupuesto.enviar');

Route::get('/contacto', function () {
    return view('content.web.contacto');})->name('contacto');


Route::post('/contacto/enviar', [FormController::class, 'contacto'])->name('form.contacto');
