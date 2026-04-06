<?php

namespace App\Filament\Resources;

use App\Models\VariantePrecio;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VariantePrecioResource extends Resource
{
    protected static ?string $model = VariantePrecio::class;

    protected static ?string $navigationLabel = 'Precios de Variantes';

    protected static ?string $label = 'Precio de Variante';

    protected static ?string $pluralLabel = 'Precios de Variantes';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-s-currency-dollar';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('variante_id')
                    ->label('Variante')
                    ->relationship('variante', 'sku', fn ($query) => $query->with('producto'))
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->producto->nombre . ' - ' . $record->sku)
                    ->required(),
                TextInput::make('ultimo_costo')
                    ->label('Último Costo')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0),
                TextInput::make('margen_porcentaje')
                    ->label('Margen (%)')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0),
                TextInput::make('precio_base')
                    ->label('Precio Base')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0),
                TextInput::make('precio_final')
                    ->label('Precio Final')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->helperText('Se calcula automáticamente: precio_base + (precio_base * margen / 100)'),
                DateTimePicker::make('fecha_actualizacion')
                    ->label('Fecha de Actualización'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('variante.producto.nombre')
                    ->label('Producto')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('variante.sku')
                    ->label('SKU')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ultimo_costo')
                    ->label('Último Costo')
                    ->sortable()
                    ->money('COP'),
                Tables\Columns\TextColumn::make('margen_porcentaje')
                    ->label('Margen (%)')
                    ->sortable()
                    ->suffix('%'),
                Tables\Columns\TextColumn::make('precio_base')
                    ->label('Precio Base')
                    ->sortable()
                    ->money('COP'),
                Tables\Columns\TextColumn::make('precio_final')
                    ->label('Precio Final')
                    ->sortable()
                    ->money('COP'),
                Tables\Columns\TextColumn::make('fecha_actualizacion')
                    ->label('Actualización')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('precio_final')
                    ->label('Con precio final')
                    ->query(fn ($query) => $query->whereNotNull('precio_final')),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make()
                    ->label('Nuevo Precio'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\VariantePrecioResource\Pages\ListVariantePrecios::route('/'),
            'create' => \App\Filament\Resources\VariantePrecioResource\Pages\CreateVariantePrecio::route('/create'),
            'edit' => \App\Filament\Resources\VariantePrecioResource\Pages\EditVariantePrecio::route('/{record}/edit'),
        ];
    }
}