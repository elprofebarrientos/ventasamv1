<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\RecepcionCompra;
use App\Models\RecepcionDetalle;
use App\Models\InventarioUbicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecepcionController extends Controller
{
    public function getDetalles($compraId)
    {
        $detalles = \App\Models\CompraDetalle::with(['variante.producto', 'variante.valores.atributo'])
            ->where('id_compra', $compraId)
            ->get();
        
        return response()->json($detalles);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'id_compra' => 'required|exists:compras,id_compra',
            'observacion' => 'nullable|string',
            'detalles' => 'required|array|min:1',
            'detalles.*.id_bodega' => 'required|exists:bodega,id_bodega',
            'detalles.*.id_ubicacion' => 'required|exists:ubicaciones,id_ubicacion',
            'detalles.*.cantidad_recibida' => 'required|numeric|min:0',
        ]);
        
        DB::beginTransaction();
        
        try {
            $recepcion = RecepcionCompra::create([
                'id_compra' => $request->id_compra,
                'fecha' => now()->toDateString(),
                'observacion' => $request->observacion,
                'estado' => 'COMPLETADA',
                'created_by' => auth()->id(),
            ]);
            
            $resultadoRecepcion = 'Completa';
            
            foreach ($request->detalles as $detalle) {
                $cantidadComprada = floatval($detalle['cantidad_comprada'] ?? 0);
                $cantidadRecibida = floatval($detalle['cantidad_recibida'] ?? 0);
                
                if ($cantidadRecibida < $cantidadComprada) {
                    $resultadoRecepcion = 'Incompleta';
                } elseif ($cantidadRecibida > $cantidadComprada) {
                    $resultadoRecepcion = 'Mixta';
                }
                
                RecepcionDetalle::create([
                    'id_recepcion' => $recepcion->id_recepcion,
                    'id_variante' => $detalle['id_variante'],
                    'id_bodega' => $detalle['id_bodega'],
                    'id_ubicacion' => $detalle['id_ubicacion'],
                    'cantidad_recibida' => $cantidadRecibida,
'created_by' => 1,
                ]);
                
                $inventario = InventarioUbicacion::where('id_variante', $detalle['id_variante'])
                    ->where('id_ubicacion', $detalle['id_ubicacion'])
                    ->first();
                
                if ($inventario) {
                    $inventario->stock_actual = $inventario->stock_actual + $cantidadRecibida;
                    $inventario->save();
                } else {
                    InventarioUbicacion::create([
                        'id_variante' => $detalle['id_variante'],
                        'id_ubicacion' => $detalle['id_ubicacion'],
                        'stock_actual' => $cantidadRecibida,
                        'stock_reservado' => 0,
                    ]);
                }
            }
            
            $compra = Compra::find($request->id_compra);
            $compra->resultado_recepcion = $resultadoRecepcion;
            $compra->save();
            
            DB::commit();
            
            return response()->json(['success' => true, 'message' => 'Recepción guardada correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }
}