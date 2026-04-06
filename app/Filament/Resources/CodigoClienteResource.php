<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CodigoClienteResource\Pages;
use App\Models\CodigoCliente;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CodigoClienteResource extends Resource
{
    protected static ?string $model = CodigoCliente::class;

    protected static ?string $navigationLabel = 'Códigos de Cliente';

    protected static ?string $label = 'Código de Cliente';

    protected static ?string $pluralLabel = 'Códigos de Cliente';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-s-ticket';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('codigo')
                    ->label('Código')
                    ->required()
                    ->maxLength(50)
                    ->unique('codigo_cliente', 'codigo'),
                Forms\Components\Select::make('tipo_descuento')
                    ->label('Tipo de Descuento')
                    ->required()
                    ->options([
                        'porcentaje' => 'Porcentaje',
                        'valor' => 'Valor Fijo',
                    ]),
                Forms\Components\TextInput::make('valor')
                    ->label('Valor')
                    ->required()
                    ->numeric()
                    ->step(0.01),
                Forms\Components\Select::make('tipo_uso')
                    ->label('Tipo de Uso')
                    ->required()
                    ->options([
                        'unico' => 'Único',
                        'multiple' => 'Múltiple',
                    ]),
                Forms\Components\TextInput::make('max_usos')
                    ->label('Máximo de Usos')
                    ->numeric()
                    ->nullable(),
                Forms\Components\DateTimePicker::make('fecha_inicio')
                    ->label('Fecha de Inicio')
                    ->required(),
                Forms\Components\DateTimePicker::make('fecha_fin')
                    ->label('Fecha de Fin')
                    ->required(),
                Forms\Components\TextInput::make('monto_minimo')
                    ->label('Monto Mínimo')
                    ->numeric()
                    ->step(0.01)
                    ->nullable(),
                Forms\Components\Select::make('aplica_a')
                    ->label('Aplica a')
                    ->default('todos')
                    ->options([
                        'producto' => 'Producto',
                        'categoria' => 'Categoría',
                        'todos' => 'Todos',
                    ])
                    ->live(),
                Forms\Components\Select::make('producto_ids')
                    ->label('Productos')
                    ->multiple()
                    ->options(\App\Models\Producto::all()->pluck('nombre', 'id_producto'))
                    ->visible(fn ($get) => $get('aplica_a') === 'producto'),
                Forms\Components\Select::make('categoria_ids')
                    ->label('Categorías')
                    ->multiple()
                    ->options(\App\Models\Categoria::all()->pluck('nombre', 'id_categoria'))
                    ->visible(fn ($get) => $get('aplica_a') === 'categoria'),
                Forms\Components\Toggle::make('activo')
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
                Tables\Columns\TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo_descuento')
                    ->label('Tipo de Descuento')
                    ->sortable(),
                Tables\Columns\TextColumn::make('valor')
                    ->label('Valor')
                    ->sortable()
                    ->formatStateUsing(fn ($state, $record) => 
                        $record->tipo_descuento === 'porcentaje' 
                            ? $state . '%' 
                            : '$' . number_format($state, 2)
                    ),
                Tables\Columns\TextColumn::make('tipo_uso')
                    ->label('Tipo de Uso'),
                Tables\Columns\TextColumn::make('usos_actuales')
                    ->label('Usos')
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_usos')
                    ->label('Máx. Usos')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->label('Inicio')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_fin')
                    ->label('Fin')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('activo')
                    ->label('Activo'),
            ])
            ->filters([
                Tables\Filters\Filter::make('activo')
                    ->label('Solo activos')
                    ->query(fn ($query) => $query->where('activo', true)),
                Tables\Filters\Filter::make('vigentes')
                    ->label('Vigentes')
                    ->query(fn ($query) => $query->where('fecha_inicio', '<=', now())->where('fecha_fin', '>=', now())),
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
                    ->label('Nuevo Código'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCodigoClientes::route('/'),
            'create' => Pages\CreateCodigoCliente::route('/create'),
            'edit' => Pages\EditCodigoCliente::route('/{record}/edit'),
        ];
    }
}
