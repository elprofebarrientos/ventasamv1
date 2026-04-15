<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventarioDisponibleResource\Pages;
use App\Models\InventarioDisponible;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class InventarioDisponibleResource extends Resource
{
    protected static ?string $model = InventarioDisponible::class;

    protected static \BackedEnum | string | null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Inventario Disponible';

    protected static string | UnitEnum | null $navigationGroup = 'Ventas';

    protected static ?string $modelLabel = 'Inventario';

    protected static ?string $pluralModelLabel = 'Inventario';

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
                Tables\Columns\TextColumn::make('ultima_actualizacion')
                    ->label('Última Actualización')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventarioDisponible::route('/'),
        ];
    }
}