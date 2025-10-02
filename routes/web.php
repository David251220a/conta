<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ConsultaController;
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

    Route::get('/sifen/{factura}/ver', [SifenController::class, 'enviar_sifen'])->name('sifen.enviar_sifen');
    Route::post('/sifen/{sifen}/reenviar', [SifenController::class, 'reenviar_sifen'])->name('sifen.reenviar_sifen');

    Route::get('/consulta/facturas-en-espera-o-rechazado', [ConsultaController::class, 'facturas'])->name('consulta.factura_pendiente');
});
