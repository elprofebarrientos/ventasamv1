<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Products;
use App\Filament\Resources\UnidadMedidaResource\Pages;
use App\Models\UnidadMedida;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class UnidadMedidaResource extends Resource
{
    protected static ?string $model = UnidadMedida::class;

    protected static string | UnitEnum | null $navigationGroup = 'Productos';

    protected static ?string $navigationLabel = 'Unidades de Medida';

    protected static ?string $label = 'Unidad de Medida';

    protected static ?string $pluralLabel = 'Unidades de Medida';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-s-scale';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('abreviatura')
                    ->label('Abreviatura')
                    ->required()
                    ->maxLength(10),
                Forms\Components\Select::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'unidad' => 'Unidad',
                        'peso' => 'Peso',
                        'volumen' => 'Volumen',
                        'longitud' => 'Longitud',
                        'superficie' => 'Superficie',
                    ])
                    ->required(),
                Forms\Components\Toggle::make('activo')
                    ->label('Activo')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_unidad_medida')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('abreviatura')
                    ->label('Abreviatura')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->colors([
                        'primary' => 'unidad',
                        'success' => 'peso',
                        'warning' => 'volumen',
                        'danger' => 'longitud',
                        'gray' => 'superficie',
                    ]),
                Tables\Columns\BooleanColumn::make('activo')
                    ->label('Activo'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('activo')
                    ->label('Solo activas')
                    ->query(fn ($query) => $query->where('activo', true)),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->modal()
                    ->label('Nueva Unidad')
                    ->successNotificationTitle('Creado correctamente'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUnidadMedidas::route('/'),
            'create' => Pages\CreateUnidadMedida::route('/create'),
            'edit' => Pages\EditUnidadMedida::route('/{record}/edit'),
        ];
    }
}