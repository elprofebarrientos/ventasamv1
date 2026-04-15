<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\RecepcionCompra;
use App\Models\RecepcionDetalle;
use App\Models\InventarioUbicacion;
use App\Models\InventarioDisponible;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecepcionController extends Controller
{
    public function getDetalles($compraId, $incluirRecibidos = false)
    {
        try {
            $detalles = \App\Models\CompraDetalle::with(['variante.producto', 'variante.valores.atributo'])
                ->where('id_compra', $compraId)
                ->get();
            
            if (!$incluirRecibidos) {
                $detalles = $detalles->filter(function ($detalle) {
                    $detalle->cantidad_pendiente = floatval($detalle->cantidad) - floatval($detalle->cantidad_recibida ?? 0);
                    return $detalle->cantidad_pendiente > 0;
                });
            }
            
            return $detalles->map(function ($detalle) {
                    $detalle->cantidad_pendiente = floatval($detalle->cantidad) - floatval($detalle->cantidad_recibida ?? 0);
                    
                    $detalle->variante_tiene_lote = $detalle->variante ? (bool) $detalle->variante->tiene_lote : false;
                    $detalle->variante_tiene_fecha_vencimiento = $detalle->variante ? (bool) $detalle->variante->tiene_fecha_vencimiento : false;
                    
                    $recepcionesDetalle = RecepcionDetalle::with(['recepcion', 'bodega.sucursal', 'ubicacion'])
                        ->where('id_variante', $detalle->id_variante)
                        ->whereHas('recepcion', function ($q) use ($detalle) {
                            $q->where('id_compra', $detalle->id_compra);
                        })
                        ->get();
                    
                    $detalle->recepciones = $recepcionesDetalle->map(function ($rd) {
                        $sucursal = null;
                        if ($rd->bodega && $rd->bodega->sucursal) {
                            $sucursal = $rd->bodega->sucursal->nombre;
                        }
                        return [
                            'id_recepcion' => $rd->id_recepcion,
                            'cantidad_recibida' => $rd->cantidad_recibida,
                            'bodega' => $rd->bodega ? $rd->bodega->nombre : null,
                            'ubicacion' => $rd->ubicacion ? $rd->ubicacion->nombre : null,
                            'sucursal' => $sucursal,
                            'fecha_vencimiento' => $rd->fecha_vencimiento,
                            'fecha_recepcion' => $rd->recepcion ? \Carbon\Carbon::parse($rd->recepcion->fecha)->format('Y-m-d') : null,
                        ];
                    });
                    
                    return $detalle;
                });
            
            return response()->json($detalles);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function getRecepcionesPorCompra($compraId)
    {
        $recepciones = RecepcionCompra::with(['detalles.variante.producto', 'detalles.variante.valores.atributo'])
            ->where('id_compra', $compraId)
            ->orderBy('fecha', 'desc')
            ->get()
            ->map(function ($recepcion) {
                $detallesFormateados = $recepcion->detalles->map(function ($detalle) {
                    $atributos = '';
                    if ($detalle->variante && $detalle->variante->valores) {
                        $atributos = $detalle->variante->valores->map(function ($v) {
                            return ($v->atributo ? $v->atributo->nombre : '') . ': ' . ($v->valor ?? '');
                        })->filter()->implode(', ');
                    }
                    
                    $nombreProducto = $detalle->variante && $detalle->variante->producto 
                        ? $detalle->variante->producto->nombre 
                        : 'Producto no encontrado';
                    
                    return [
                        'id_detalle' => $detalle->id_detalle,
                        'cantidad_recibida' => $detalle->cantidad_recibida,
                        'producto_nombre' => $nombreProducto,
                        'atributos' => $atributos,
                        'sku' => $detalle->variante ? $detalle->variante->sku : '',
                    ];
                });
                
                return [
                    'id_recepcion' => $recepcion->id_recepcion,
                    'fecha' => $recepcion->fecha,
                    'observacion' => $recepcion->observacion,
                    'estado' => $recepcion->estado,
                    'detalles' => $detallesFormateados,
                ];
            });
        
        return response()->json($recepciones);
    }
    
    public function getDetallesRecepcion($idRecepcion)
    {
        $detalles = RecepcionDetalle::with([
            'variante.producto',
            'variante.valores.atributo',
            'bodega',
            'ubicacion'
        ])
            ->where('id_recepcion', $idRecepcion)
            ->get();
        
        $result = $detalles->map(function ($detalle) {
            $varianteData = null;
            if ($detalle->variante) {
                $varianteData = [
                    'id_variante' => $detalle->variante->id_variante,
                    'tiene_lote' => (bool) $detalle->variante->tiene_lote,
                    'tiene_fecha_vencimiento' => (bool) $detalle->variante->tiene_fecha_vencimiento,
                    'producto' => $detalle->variante->producto ? [
                        'nombre' => $detalle->variante->producto->nombre
                    ] : null,
                    'valores' => $detalle->variante->valores->map(function ($v) {
                        return [
                            'valor' => $v->valor,
                            'atributo' => $v->atributo ? [
                                'nombre' => $v->atributo->nombre
                            ] : null
                        ];
                    })->toArray()
                ];
            }
            
            return [
                'id_detalle' => $detalle->id_detalle,
                'id_variante' => $detalle->id_variante,
                'cantidad_recibida' => $detalle->cantidad_recibida,
                'lote' => $detalle->lote,
                'fecha_vencimiento' => $detalle->fecha_vencimiento,
                'variante' => $varianteData,
                'bodega' => $detalle->bodega ? ['nombre' => $detalle->bodega->nombre] : null,
                'ubicacion' => $detalle->ubicacion ? ['nombre' => $detalle->ubicacion->nombre] : null
            ];
        });
        
        return response()->json($result);
    }
    
    public function getRecepcion($idRecepcion)
    {
        $recepcion = RecepcionCompra::with(['compra.proveedor', 'detalles.variante.producto', 'detalles.bodega', 'detalles.ubicacion'])
            ->where('id_recepcion', $idRecepcion)
            ->first();
        
        return response()->json($recepcion);
    }
    
    public function update(Request $request, $idRecepcion)
    {
        $request->validate([
            'fecha_recepcion' => 'nullable|date',
            'numero_recepcion' => 'nullable|string|max:255',
            'observacion' => 'nullable|string',
        ]);
        
        $recepcion = RecepcionCompra::findOrFail($idRecepcion);
        $recepcion->fecha_recepcion = $request->fecha_recepcion;
        $recepcion->numero_recepcion = $request->numero_recepcion;
        $recepcion->observacion = $request->observacion;
        $recepcion->save();
        
        return response()->json(['success' => true, 'message' => 'Recepción actualizada correctamente']);
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
                $idDetalle = $detalle['id_detalle'] ?? null;
                
                $compraDetalle = \App\Models\CompraDetalle::find($idDetalle);
                $cantidadRecibidaAnterior = $compraDetalle ? floatval($compraDetalle->cantidad_recibida ?? 0) : 0;
                $cantidadPendiente = $cantidadComprada - $cantidadRecibidaAnterior;
                
                if ($cantidadRecibida > $cantidadPendiente) {
                    return response()->json(['success' => false, 'message' => 'La cantidad recibida no puede ser mayor a la cantidad pendiente (' . $cantidadPendiente . ')'], 422);
                }
                
                if ($compraDetalle) {
                    $nuevaCantidadRecibida = $cantidadRecibidaAnterior + $cantidadRecibida;
                    $compraDetalle->cantidad_recibida = $nuevaCantidadRecibida;
                    $compraDetalle->save();
                }
                
                if (($cantidadRecibidaAnterior + $cantidadRecibida) < $cantidadComprada) {
                    $resultadoRecepcion = 'Incompleta';
                }
                
                RecepcionDetalle::create([
                    'id_recepcion' => $recepcion->id_recepcion,
                    'id_variante' => $detalle['id_variante'],
                    'id_bodega' => $detalle['id_bodega'],
                    'id_ubicacion' => $detalle['id_ubicacion'],
                    'cantidad_recibida' => $cantidadRecibida,
                    'lote' => $detalle['lote'] ?? null,
                    'fecha_vencimiento' => $detalle['fecha_vencimiento'] ?? null,
                    'created_by' => 1,
                ]);
                
                $inventario = InventarioUbicacion::where('id_variante', $detalle['id_variante'])
                    ->where('id_bodega', $detalle['id_bodega'])
                    ->where('id_ubicacion', $detalle['id_ubicacion'])
                    ->first();
                
                if ($inventario) {
                    $inventario->stock_actual = $inventario->stock_actual + $cantidadRecibida;
                    $inventario->lote = $detalle['lote'] ?? null;
                    $inventario->fecha_vencimiento = $detalle['fecha_vencimiento'] ?? null;
                    $inventario->save();
                } else {
                    InventarioUbicacion::create([
                        'id_variante' => $detalle['id_variante'],
                        'id_bodega' => $detalle['id_bodega'],
                        'id_ubicacion' => $detalle['id_ubicacion'],
                        'stock_actual' => $cantidadRecibida,
                        'stock_reservado' => 0,
                        'lote' => $detalle['lote'] ?? null,
                        'fecha_vencimiento' => $detalle['fecha_vencimiento'] ?? null,
                    ]);
                }
                
                InventarioDisponible::actualizarDesdeUbicacion($detalle['id_variante']);
            }
            
            $compra = Compra::with('detalles')->find($request->id_compra);
            
            $todosRecibidos = true;
            foreach ($compra->detalles as $detalle) {
                $cantidadComprada = floatval($detalle->cantidad);
                $cantidadRecibida = floatval($detalle->cantidad_recibida ?? 0);
                if ($cantidadRecibida < $cantidadComprada) {
                    $todosRecibidos = false;
                    break;
                }
            }
            
            $compra->resultado_recepcion = $todosRecibidos ? 'Completa' : 'Incompleta';
            $compra->save();
            
            DB::commit();
            
            return response()->json(['success' => true, 'message' => 'Recepción guardada correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }
}