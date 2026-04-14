<?php

namespace App\Filament\Resources\SucursalResource\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;

class BodegasRelationManager extends RelationManager
{
    protected static string $relationship = 'bodegas';

    protected static ?string $title = 'Bodegas';

    protected static ?string $recordTitleAttribute = 'nombre';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
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

    public function table(Table $table): Table
    {
        return $table
            ->columns([
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
            ])
            ->headerActions([
                Actions\CreateAction::make()
                    ->label('Nueva Bodega'),
            ])
            ->actions([
                Actions\EditAction::make()
                    ->label('Editar'),
            ]);
    }
}