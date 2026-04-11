<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RecepcionController;
use App\Models\Bodega;
use App\Models\Ubicacion;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/compras/{compraId}/detalles', [RecepcionController::class, 'getDetalles']);
Route::post('/api/recepciones/store', [RecepcionController::class, 'store'])->name('api.recepciones.store');

Route::get('/api/bodegas', function () {
    return response()->json(Bodega::where('estado', true)->get(['id_bodega', 'nombre']));
});

Route::get('/api/bodegas/{bodegaId}/ubicaciones', function ($bodegaId) {
    return response()->json(Ubicacion::where('id_bodega', $bodegaId)->where('estado', true)->get(['id_ubicacion', 'id_bodega', 'nombre']));
});
