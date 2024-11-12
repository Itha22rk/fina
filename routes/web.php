<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\ClienteController; 
use App\Http\Controllers\PagoController;
use App\Http\Controllers\CotizacionController;

use App\Http\Controllers\CorteDiarioController;
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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('clientes/{cliente}/prestamos', function(Cliente $cliente) {
    $prestamoActivo = $cliente->prestamos()->where('is_active', true)->first();

    return response()->json([
        'prestamo' => $prestamoActivo,
    ]);
});
Route::put('/pagos/{id}', [PagoController::class, 'actualizarPago'])->name('pagos.actualizar');
Route::get('/cotizacion', [CotizacionController::class, 'index'])->name('cotizacion.index');
Route::post('/cotizacion', [CotizacionController::class, 'store'])->name('cotizacion.store');

Route::get('/corte-semanal', [CorteDiarioController::class, 'resumenSemanal'])->name('corte.semanal');
Route::get('/pagos/cliente/{cliente}', 'PagoController@getPagosCliente');
// web.php
Route::get('/pagos/cliente/{clienteId}/pendientes', [PagoController::class, 'getPagosPendientes']);

Route::get('/prestamos/crear', [PrestamoController::class, 'create'])->name('prestamos.crear');
Route::get('/cortes-resumen', [CorteDiarioController::class, 'resumenDiario'])->name('cortes.resumen');
Route::get('/pagos', [PagoController::class, 'index']);
Auth::routes();
Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RolController::class);
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('clientes', ClienteController::class); 
    Route::resource('prestamos', PrestamoController::class);
    Route::resource('pagos', PagoController::class);
    Route::resource('cortes', CorteDiarioController::class);
    Route::resource('cotizacion', CotizacionController::class);
});
