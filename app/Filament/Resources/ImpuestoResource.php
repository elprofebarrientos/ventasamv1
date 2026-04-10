<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ImpuestoResource\Pages;
use App\Models\Impuesto;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ImpuestoResource extends Resource
{
    protected static ?string $model = Impuesto::class;

    protected static ?string $navigationLabel = 'Impuestos';

    protected static ?string $label = 'Impuesto';

    protected static ?string $pluralLabel = 'Impuestos';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-s-receipt-percent';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(50)
                    ->placeholder('ej: IVA, INC, ICA'),
                Select::make('tipo')
                    ->label('Tipo')
                    ->required()
                    ->options([
                        'porcentaje' => 'Porcentaje',
                        'fijo' => 'Fijo',
                    ])
                    ->live(),
                TextInput::make('valor')
                    ->label('Valor')
                    ->required()
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->helperText(fn ($get) => $get('tipo') === 'porcentaje' ? 'Porcentaje: ej 19 para 19%' : 'Fijo: ej 1000'),
                Toggle::make('activo')
                    ->label('Activo')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->sortable(),
                Tables\Columns\TextColumn::make('valor')
                    ->label('Valor')
                    ->sortable()
                    ->formatStateUsing(fn ($state, $record) => $record->tipo === 'porcentaje' ? $state . '%' : number_format($state, 2)),
                Tables\Columns\BooleanColumn::make('activo')
                    ->label('Activo'),
            ])
            ->filters([
                Tables\Filters\Filter::make('activo')
                    ->label('Solo activos')
                    ->query(fn ($query) => $query->where('activo', true)),
            ])
            ->actions([
                EditAction::make()
                    ->successNotificationTitle('Guardado correctamente'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListImpuestos::route('/'),
        ];
    }
}
