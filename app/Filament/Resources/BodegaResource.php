<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BodegaResource\Pages;
use App\Models\Bodega;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class BodegaResource extends Resource
{
    protected static ?string $model = Bodega::class;

    protected static \BackedEnum | string | null $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationLabel = 'Bodegas';

    protected static string | UnitEnum | null $navigationGroup = 'Configurar';

    protected static ?string $modelLabel = 'Bodega';

    protected static ?string $pluralModelLabel = 'Bodegas';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Forms\Components\Select::make('id_sucursal')
                    ->label('Sucursal')
                    ->relationship('sucursal', 'nombre')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('codigo')
                    ->label('Código')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('responsable')
                    ->label('Responsable')
                    ->maxLength(255),
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
                Tables\Columns\TextColumn::make('sucursal.nombre')
                    ->label('Sucursal')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsable')
                    ->label('Responsable')
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
                    ->options([
                        'activa' => 'Activa',
                        'inactiva' => 'Inactiva',
                    ]),
            ])
            ->actions([
                EditAction::make()
                    ->label('Editar'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Nueva Bodega'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBodegas::route('/'),
            'create' => Pages\CreateBodega::route('/create'),
            'edit' => Pages\EditBodega::route('/{record}/edit'),
        ];
    }
}