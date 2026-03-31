<?php

namespace App\Filament\Resources\CompraResource\Schemas;

use App\Models\Compra;
use App\Models\Producto;
use App\Models\Proveedor;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class CompraForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Datos de la Compra del proveedor')
                ->columnSpanFull()
                ->columns(5)
                ->schema([
                    Select::make('id_proveedor')
                        ->label('Proveedor')
                        ->options(fn () => Proveedor::query()->where('estado', true)->pluck('razon_social', 'id_proveedor'))
                        ->searchable()
                        ->preload()
                        ->required()
                        ->disabled(fn ($get) => static::hasAbonos($get)),

                    TextInput::make('numero_factura')
                        ->label('Número de Factura')
                        ->maxLength(50)
                        ->disabled(fn ($get) => static::hasAbonos($get)),

                    TextInput::make('cufe')
                        ->label('CUFE (Código DIAN)')
                        ->maxLength(255)
                        ->disabled(fn ($get) => static::hasAbonos($get)),

                    DatePicker::make('fecha_emision')
                        ->label('Fecha de Emisión')
                        ->required()
                        ->disabled(fn ($get) => static::hasAbonos($get)),

                    DatePicker::make('fecha_vencimiento')
                        ->label('Fecha de Vencimiento')
                        ->disabled(fn ($get) => static::hasAbonos($get)),
                ]),

            Section::make('Detalle de Productos')
                ->columnSpanFull()
                ->schema([
                    Repeater::make('detalles')
                        ->label('')
                        ->relationship('detalles')
                        ->schema([
                            Select::make('id_producto')
                                ->label('Producto')
                                ->options(fn ($get) => static::getAvailableProducts($get))
                                ->searchable()
                                ->required()
                                ->disabled(fn ($get) => static::hasAbonos($get)),

                            TextInput::make('cantidad')
                                ->label('Cantidad')
                                ->numeric()
                                ->required()
                                ->minValue(0.01)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($set, $get) => static::recalcularTotales($set, $get))
                                ->disabled(fn ($get) => static::hasAbonos($get)),

                            TextInput::make('costo_unitario')
                                ->label('Costo Unitario (sin IVA)')
                                ->numeric()
                                ->prefix('$')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($set, $get) => static::recalcularTotales($set, $get))
                                ->disabled(fn ($get) => static::hasAbonos($get)),

                            TextInput::make('porcentaje_iva')
                                ->label('% IVA')
                                ->numeric()
                                ->suffix('%')
                                ->default(0)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($set, $get) => static::recalcularTotales($set, $get))
                                ->disabled(fn ($get) => static::hasAbonos($get)),
                        ])
                        ->columns(4)
                        ->addActionLabel('Agregar producto')
                        ->live()
                        ->afterStateUpdated(fn ($set, $get) => static::recalcularTotalesFromRoot($set, $get))
                        ->disabled(fn ($get) => static::hasAbonos($get))
                        ->deletable(fn ($get) => !static::hasAbonos($get)),
                ]),

            Section::make('Totales')
                ->columnSpanFull()
                ->columns(5)
                ->schema([
                    TextInput::make('subtotal_bruto')
                        ->label('Subtotal Bruto')
                        ->numeric()
                        ->prefix('$')
                        ->default(0)
                        ->disabled()
                        ->dehydrated(),

                    TextInput::make('total_iva')
                        ->label('Total IVA')
                        ->numeric()
                        ->prefix('$')
                        ->default(0)
                        ->disabled()
                        ->dehydrated(),

                    TextInput::make('valor_retefuente')
                        ->label('Retención en la Fuente')
                        ->numeric()
                        ->prefix('$')
                        ->default(0)
                        ->disabled(fn ($get) => static::hasAbonos($get)),

                    TextInput::make('valor_reteica')
                        ->label('ReteICA')
                        ->numeric()
                        ->prefix('$')
                        ->default(0)
                        ->disabled(fn ($get) => static::hasAbonos($get)),

                    TextInput::make('total_neto_pagar')
                        ->label('Total Neto a Pagar')
                        ->numeric()
                        ->prefix('$')
                        ->default(0)
                        ->disabled()
                        ->dehydrated(),
                ]),
        ]);
    }

    /**
     * Check if the purchase has abonos
     */
    private static function hasAbonos(Get $get): bool
    {
        // Get the record ID from the form
        $recordId = $get('id_compra');

        if (!$recordId) {
            return false;
        }

        // Check if the compra has any abonos
        $compra = Compra::find($recordId);

        if (!$compra) {
            return false;
        }

        return $compra->abonos()->count() > 0;
    }

    /**
     * Get available products excluding those already added to this purchase
     */
    private static function getAvailableProducts(Get $get): \Illuminate\Support\Collection
    {
        $recordId = $get('id_compra');

        $products = Producto::query()->orderBy('nombre')->get();

        // Get the current product being edited in this row (if any)
        $currentProductId = $get('id_producto');

        if ($recordId) {
            $compra = Compra::find($recordId);

            if ($compra && $compra->detalles()->count() > 0) {
                // Get IDs of products already in this purchase
                $existingProductIds = $compra->detalles()->pluck('id_producto')->toArray();

                // Exclude those products, but keep the current one being edited
                $products = $products->filter(function ($product) use ($existingProductIds, $currentProductId) {
                    // Always include the product currently being edited
                    if ($currentProductId && $product->id_producto == $currentProductId) {
                        return true;
                    }
                    // Exclude products that are already in other rows
                    return !in_array($product->id_producto, $existingProductIds);
                });
            }
        }

        // Return as key-value array with id as key and name as value
        return $products->pluck('nombre', 'id_producto');
    }

    /**
     * Called from inside a repeater row field (two levels up: row → repeater → form root).
     */
    private static function recalcularTotales(Set $set, Get $get): void
    {
        $detalles = $get('../../detalles') ?? [];

        static::calcular($set, $get, $detalles, '../../');
    }

    /**
     * Called from the repeater itself (one level up: repeater → form root).
     */
    private static function recalcularTotalesFromRoot(Set $set, Get $get): void
    {
        $detalles = $get('detalles') ?? [];

        static::calcular($set, $get, $detalles, '');
    }

    private static function calcular(Set $set, Get $get, array $detalles, string $prefix): void
    {
        $subtotal = collect($detalles)->sum(
            fn ($d) => floatval($d['cantidad'] ?? 0) * floatval($d['costo_unitario'] ?? 0)
        );

        $iva = collect($detalles)->sum(
            fn ($d) => floatval($d['cantidad'] ?? 0) * floatval($d['costo_unitario'] ?? 0) * (floatval($d['porcentaje_iva'] ?? 0) / 100)
        );

        $retefuente = floatval($get($prefix . 'valor_retefuente') ?? 0);
        $reteica    = floatval($get($prefix . 'valor_reteica') ?? 0);

        $set($prefix . 'subtotal_bruto', round($subtotal, 2));
        $set($prefix . 'total_iva', round($iva, 2));
        $set($prefix . 'total_neto_pagar', round($subtotal + $iva - $retefuente - $reteica, 2));
    }
}
