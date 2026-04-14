<?php

namespace App\Filament\Resources\CompraResource\Schemas;

use App\Models\Compra;
use App\Models\Producto;
use App\Models\ProductoVariante;
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
                        ->disabled(fn ($get) => static::isLocked($get)),

                    TextInput::make('numero_factura')
                        ->label('Número de Factura')
                        ->maxLength(50)
                        ->disabled(fn ($get) => static::isLocked($get)),

                    TextInput::make('cufe')
                        ->label('CUFE (Código DIAN)')
                        ->maxLength(255)
                        ->disabled(fn ($get) => static::isLocked($get)),

                    DatePicker::make('fecha_emision')
                        ->label('Fecha de Emisión')
                        ->required()
                        ->disabled(fn ($get) => static::isLocked($get)),

                    DatePicker::make('fecha_vencimiento')
                        ->label('Fecha de Vencimiento')
                        ->disabled(fn ($get) => static::isLocked($get)),
                ]),

            Section::make('Detalle de Productos')
                ->columnSpanFull()
                ->schema([
                    Repeater::make('detalles')
                        ->label('')
                        ->relationship('detalles')
                        ->schema([
                            Select::make('id_variante')
                                ->label('Producto / Variante')
                                ->options(fn ($get) => static::getAvailableVariants($get))
                                ->getSearchResultsUsing(fn (string $search) => static::searchVariants($search))
                                ->searchable()
                                ->required()
                                ->disabled(fn ($get) => static::isLocked($get)),

                            TextInput::make('cantidad')
                                ->label('Cantidad')
                                ->numeric()
                                ->required()
                                ->minValue(0.01)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($set, $get) => static::recalcularTotales($set, $get))
                                ->disabled(fn ($get) => static::isLocked($get)),

                            TextInput::make('costo_unitario')
                                ->label('Costo Unitario (sin IVA)')
                                ->numeric()
                                ->prefix('$')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($set, $get) => static::recalcularTotales($set, $get))
                                ->disabled(fn ($get) => static::isLocked($get)),

                            TextInput::make('porcentaje_iva')
                                ->label('% IVA')
                                ->numeric()
                                ->suffix('%')
                                ->default(0)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($set, $get) => static::recalcularTotales($set, $get))
                                ->disabled(fn ($get) => static::isLocked($get)),
                        ])
                        ->columns(4)
                        ->addActionLabel('Agregar producto')
                        ->live()
                        ->afterStateUpdated(fn ($set, $get) => static::recalcularTotalesFromRoot($set, $get))
                        ->disabled(fn ($get) => static::isLocked($get))
                        ->deletable(fn ($get) => !static::isLocked($get)),
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
                        ->disabled(fn ($get) => static::isLocked($get)),

                    TextInput::make('valor_reteica')
                        ->label('ReteICA')
                        ->numeric()
                        ->prefix('$')
                        ->default(0)
                        ->disabled(fn ($get) => static::isLocked($get)),

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
        $recordId = $get('id_compra');

        if (!$recordId) {
            return false;
        }

        $compra = Compra::find($recordId);

        if (!$compra) {
            return false;
        }

        return $compra->abonos()->count() > 0;
    }

    /**
     * Check if the purchase has complete reception
     */
    private static function hasRecepcionCompleta(Get $get): bool
    {
        $recordId = $get('id_compra');

        if (!$recordId) {
            return false;
        }

        $compra = Compra::find($recordId);

        if (!$compra) {
            return false;
        }

        return $compra->resultado_recepcion === 'Completa';
    }

    /**
     * Check if the purchase cannot be modified (has abonos or complete reception)
     */
    private static function isLocked(Get $get): bool
    {
        return static::hasAbonos($get) || static::hasRecepcionCompleta($get);
    }

    /**
     * Get available variants excluding those already added to this purchase
     */
    private static function getAvailableVariants(Get $get): \Illuminate\Support\Collection
    {
        $recordId = $get('id_compra');

        // Get all variants with their product and attribute values
        $variants = ProductoVariante::with(['producto', 'valores.atributo'])
            ->where('activo', true)
            ->get();

        // Get the current variant being edited in this row (if any)
        $currentVariantId = $get('id_variante');

        if ($recordId) {
            $compra = Compra::find($recordId);

            if ($compra && $compra->detalles()->count() > 0) {
                // Get IDs of variants already in this purchase
                $existingVariantIds = $compra->detalles()->pluck('id_variante')->toArray();

                // Exclude those variants, but keep the current one being edited
                $variants = $variants->filter(function ($variant) use ($existingVariantIds, $currentVariantId) {
                    // Always include the variant currently being edited
                    if ($currentVariantId && $variant->id_variante == $currentVariantId) {
                        return true;
                    }
                    // Exclude variants that are already in other rows
                    return !in_array($variant->id_variante, $existingVariantIds);
                });
            }
        }

        // Build the display name for each variant: "Product Name - Attribute1: Value1, Attribute2: Value2"
        return $variants->mapWithKeys(function ($variant) {
            $productName = $variant->producto->nombre ?? 'Producto sin nombre';

            // Build attribute values string
            $attributeValues = $variant->valores
                ->map(function ($valor) {
                    $atributoNombre = $valor->atributo->nombre ?? '';
                    $valorTexto = $valor->valor ?? '';
                    return "{$atributoNombre}: {$valorTexto}";
                })
                ->filter() // Remove empty values
                ->implode(', ');

            // Format: "Product Name - Attribute1: Value1, Attribute2: Value2"
            $displayName = $attributeValues
                ? "{$productName} - {$attributeValues}"
                : $productName;

            return [(string) $variant->id_variante => $displayName];
        });
    }

    /**
     * Search variants by product name or attribute values
     */
    private static function searchVariants(string $search): array
    {
        $variants = ProductoVariante::with(['producto', 'valores.atributo'])
            ->where('activo', true)
            ->whereHas('producto', function ($query) use ($search) {
                $query->where('nombre', 'like', "%{$search}%");
            })
            ->orWhereHas('valores', function ($query) use ($search) {
                $query->where('valor', 'like', "%{$search}%");
            })
            ->orWhereHas('valores.atributo', function ($query) use ($search) {
                $query->where('nombre', 'like', "%{$search}%");
            })
            ->get();

        $results = [];
        foreach ($variants as $variant) {
            $productName = $variant->producto->nombre ?? 'Producto sin nombre';

            $attributeValues = $variant->valores
                ->map(function ($valor) {
                    $atributoNombre = $valor->atributo->nombre ?? '';
                    $valorTexto = $valor->valor ?? '';
                    return "{$atributoNombre}: {$valorTexto}";
                })
                ->filter()
                ->implode(', ');

            $displayName = $attributeValues
                ? "{$productName} - {$attributeValues}"
                : $productName;

            $results[(string) $variant->id_variante] = $displayName;
        }

        return $results;
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
