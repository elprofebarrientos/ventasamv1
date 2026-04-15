<?php

use App\Models\InventarioDisponible;

$inventario = InventarioDisponible::with(['variante.producto', 'variante.valores.atributo'])
    ->orderBy('ultima_actualizacion', 'desc')
    ->get();
?>

<x-filament-panels::page>
    <div style="background-color: #f9fafb; min-height: 100vh; padding: 1.5rem;">
        <meta http-equiv="refresh" content="30">
        <div style="max-width: 72rem; margin: 0 auto;">
            <div style="background-color: white; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <h2 style="font-size: 1.5rem; font-weight: 700; color: #1f2937;">Inventario Disponible</h2>
                            <p style="margin-top: 0.25rem; font-size: 0.875rem; color: #6b7280; margin-left: 1rem;">Stock disponible por variante</p>
                        </div>
                        <span style="background-color: #dbeafe; color: #1d4ed8; padding: 0.5rem 1rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500;">
                            {{ $inventario->count() }} productos
                        </span>
                    </div>
                </div>

                @if($inventario->isEmpty())
                    <div style="padding: 2rem; text-align: center;">
                        <svg style="margin: 0 auto; height: 3rem; width: 3rem; color: #9ca3af;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <h3 style="margin-top: 0.5rem; font-size: 0.875rem; font-weight: 500; color: #111827;">No hay inventario disponible</h3>
                    </div>
                @else
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="background-color: #f3f4f6;">
                                    <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #4b5563; text-transform: uppercase;">Producto</th>
                                    <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #4b5563; text-transform: uppercase;">Atributos</th>
                                    <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #4b5563; text-transform: uppercase;">SKU</th>
                                    <th style="padding: 1rem; text-align: right; font-size: 0.75rem; font-weight: 700; color: #4b5563; text-transform: uppercase;">Stock Actual</th>
                                    <th style="padding: 1rem; text-align: right; font-size: 0.75rem; font-weight: 700; color: #4b5563; text-transform: uppercase;">Stock Reservado</th>
                                    <th style="padding: 1rem; text-align: right; font-size: 0.75rem; font-weight: 700; color: #4b5563; text-transform: uppercase;">Stock Disponible</th>
                                    <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #4b5563; text-transform: uppercase;">Última Actualización</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inventario as $item)
                                <tr style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="padding: 1.25rem;">
                                        <div style="font-size: 0.875rem; font-weight: 600; color: #111827;">{{ $item->variante->producto->nombre ?? 'N/A' }}</div>
                                    </td>
                                    <td style="padding: 1.25rem;">
                                        <div style="font-size: 0.875rem; color: #4b5563;">
                                            @if($item->variante && $item->variante->valores && $item->variante->valores->count() > 0)
                                                {{ $item->variante->valores->map(fn($v) => ($v->atributo ? $v->atributo->nombre : '') . ': ' . $v->valor)->filter()->join(' | ') }}
                                            @else
                                                N/A
                                            @endif
                                        </div>
                                    </td>
                                    <td style="padding: 1.25rem;">
                                        <div style="font-size: 0.875rem; color: #374151;">{{ $item->variante->sku ?? 'N/A' }}</div>
                                    </td>
                                    <td style="padding: 1.25rem; text-align: right;">
                                        <div style="font-size: 0.875rem; font-weight: 600; color: #111827;">{{ number_format($item->stock_actual, 2, ',', '.') }}</div>
                                    </td>
                                    <td style="padding: 1.25rem; text-align: right;">
                                        <div style="font-size: 0.875rem; color: #4b5563;">{{ number_format($item->stock_reservado, 2, ',', '.') }}</div>
                                    </td>
                                    <td style="padding: 1.25rem; text-align: right;">
                                        <div style="font-size: 0.875rem; font-weight: 700; color: {{ $item->stock_disponible > 0 ? '#059669' : '#dc2626' }};">{{ number_format($item->stock_disponible, 2, ',', '.') }}</div>
                                    </td>
                                    <td style="padding: 1.25rem;">
                                        <div style="font-size: 0.875rem; color: #4b5563;">{{ $item->ultima_actualizacion ? $item->ultima_actualizacion->format('Y-m-d H:i') : 'N/A' }}</div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>