<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\EntidadController;
use App\Http\Controllers\EstablecimientoController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\SifenController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/logout', [LoginController::class, 'logout']);

Auth::routes();

Route::group([
    'middleware' => 'auth',
], function(){
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/factura/crear', [FacturaController::class, 'create'])->name('factura.create');
    Route::post('/factura/crear', [FacturaController::class, 'create'])->name('factura.store');
    Route::get('/factura/{factura}/ver-factura', [FacturaController::class, 'show'])->name('factura.show');
    Route::get('/factura/{factura}/editar-rechazado', [FacturaController::class, 'editar'])->name('factura.edit');
    Route::get('/factura/{factura}/eventos', [FacturaController::class, 'evento'])->name('factura.evento');
    Route::post('/factura/{factura}/eventos', [SifenController::class, 'evento_post'])->name('factura.evento_post');

    Route::get('/sifen/{factura}/ver', [SifenController::class, 'enviar_sifen'])->name('sifen.enviar_sifen');
    Route::post('/sifen/{sifen}/reenviar', [SifenController::class, 'reenviar_sifen'])->name('sifen.reenviar_sifen');

    Route::get('/factura/consulta', [ConsultaController::class, 'facturas'])->name('consulta.factura');

    Route::get('/token', [SifenController::class, 'crear_token'])->name('user.crear_token');

    Route::get('/entidad', [EntidadController::class, 'index'])->name('entidad.index');
    Route::get('/entidad/firma', [EntidadController::class, 'firma'])->name('entidad.firma');
    Route::post('/entidad/firma', [EntidadController::class, 'firma_post'])->name('entidad.firma_post');
    Route::get('/entidad/obligaciones', [EntidadController::class, 'obligaciones'])->name('entidad.obligaciones');
    Route::post('/entidad/obligaciones', [EntidadController::class, 'obligaciones_post'])->name('entidad.obligaciones_post');
    Route::get('/entidad/obligaciones/{obligaciones}/editar', [EntidadController::class, 'obligacion_editar'])->name('entidad.obligacion_editar');
    Route::post('/entidad/obligaciones/{obligaciones}/editar', [EntidadController::class, 'obligacion_editar_post'])->name('entidad.obligacion_editar_post');

    Route::get('/establecimiento', [EstablecimientoController::class, 'index'])->name('establecimiento.index');
    Route::get('/establecimiento/crear', [EstablecimientoController::class, 'create'])->name('establecimiento.create');
});
