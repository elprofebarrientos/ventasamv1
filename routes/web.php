<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RecepcionController;
use App\Models\Bodega;
use App\Models\Ubicacion;
use App\Models\Sucursal;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/compras/{compraId}/detalles', [RecepcionController::class, 'getDetalles']);
Route::post('/api/recepciones/store', [RecepcionController::class, 'store'])->name('api.recepciones.store');
Route::get('/api/recepciones/por-compra/{compraId}', [RecepcionController::class, 'getRecepcionesPorCompra']);
Route::get('/api/recepciones/{idRecepcion}/detalles', [RecepcionController::class, 'getDetallesRecepcion']);

Route::get('/api/sucursales', function () {
    return response()->json(Sucursal::where('estado', 'activa')->get(['id_sucursal', 'nombre']));
});

Route::get('/api/sucursales/{sucursalId}/bodegas', function ($sucursalId) {
    return response()->json(Bodega::where('id_sucursal', $sucursalId)->where('estado', 'activa')->get(['id_bodega', 'nombre']));
});

Route::get('/api/bodegas', function () {
    return response()->json(Bodega::where('estado', 'activa')->get(['id_bodega', 'nombre']));
});

Route::get('/api/bodegas/{bodegaId}/ubicaciones', function ($bodegaId) {
    return response()->json(Ubicacion::where('id_bodega', $bodegaId)->where('estado', true)->get(['id_ubicacion', 'id_bodega', 'nombre']));
});
