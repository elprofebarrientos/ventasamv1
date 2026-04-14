<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SucursalResource\Pages;
use App\Models\Sucursal;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class SucursalResource extends Resource
{
    protected static ?string $model = Sucursal::class;

    protected static \BackedEnum | string | null $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationLabel = 'Sucursales';

    protected static string | UnitEnum | null $navigationGroup = 'Configurar';

    protected static ?string $modelLabel = 'Sucursal';

    protected static ?string $pluralModelLabel = 'Sucursales';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Forms\Components\Select::make('id_empresa')
                    ->label('Empresa')
                    ->relationship('empresa', 'razon_social')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('direccion')
                    ->label('Dirección')
                    ->required()
                    ->maxLength(500)
                    ->rows(2),
                Forms\Components\TextInput::make('telefono')
                    ->label('Teléfono')
                    ->tel()
                    ->maxLength(20),
                Forms\Components\Select::make('estado')
                    ->label('Estado')
                    ->options([
                        'activa' => 'Activa',
                        'inactiva' => 'Inactiva',
                    ])
                    ->required()
                    ->default('activa'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('empresa.razon_social')
                    ->label('Empresa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('direccion')
                    ->label('Dirección')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'activa' => 'success',
                        'inactiva' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'activa' => 'Activa',
                        'inactiva' => 'Inactiva',
                    ]),
                Tables\Filters\SelectFilter::make('id_empresa')
                    ->label('Empresa')
                    ->relationship('empresa', 'razon_social'),
            ])
            ->actions([
                EditAction::make()
                    ->label('Editar'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Nueva Sucursal'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\SucursalResource\RelationManagers\BodegasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSucursales::route('/'),
            'edit' => Pages\EditSucursal::route('/{record}/edit'),
        ];
    }
}