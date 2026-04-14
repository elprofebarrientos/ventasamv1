<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventarioUbicacionResource\Pages;
use App\Models\InventarioUbicacion;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class InventarioUbicacionResource extends Resource
{
    protected static ?string $model = InventarioUbicacion::class;

    protected static \BackedEnum | string | null $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationLabel = 'Inventario Ubicaciones';

    protected static string | UnitEnum | null $navigationGroup = 'Compras';

    protected static ?string $modelLabel = 'Inventario de Ubicación';

    protected static ?string $pluralModelLabel = 'Inventario de Ubicaciones';

    protected static bool $canCreate = false;

    protected static bool $canEdit = false;

    protected static bool $canDelete = false;

public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('variante.producto.nombre')
                    ->label('Producto')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('variante.atributos_formateados')
                    ->label('Atributos'),
                Tables\Columns\TextColumn::make('variante.sku')
                    ->label('SKU'),
                Tables\Columns\TextColumn::make('ubicacion.bodega.nombre')
                    ->label('Bodega')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ubicacion.nombre')
                    ->label('Ubicación')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock_actual')
                    ->label('Stock Actual')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock_reservado')
                    ->label('Stock Reservado')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock_disponible')
                    ->label('Stock Disponible')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('lote')
                    ->label('Lote'),
                Tables\Columns\TextColumn::make('fecha_vencimiento')
                    ->label('Vencimiento')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('ubicacion')
                    ->label('Ubicación')
                    ->relationship('ubicacion', 'nombre')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventarioUbicaciones::route('/'),
        ];
    }
}