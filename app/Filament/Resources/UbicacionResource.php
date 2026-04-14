<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UbicacionResource\Pages;
use App\Models\Ubicacion;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class UbicacionResource extends Resource
{
    protected static ?string $model = Ubicacion::class;

    protected static \BackedEnum | string | null $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationLabel = 'Ubicaciones';

    protected static string | UnitEnum | null $navigationGroup = 'Configurar';

    protected static ?string $modelLabel = 'Ubicación';

    protected static ?string $pluralModelLabel = 'Ubicaciones';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Forms\Components\Select::make('id_bodega')
                    ->label('Bodega')
                    ->relationship('bodega', 'nombre')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('id_padre')
                    ->label('Ubicación Padre')
                    ->relationship('padre', 'nombre', modifyQueryUsing: function ($query) {
                        $idBodega = $query->getModel()->id_bodega ?? null;
                        if ($idBodega) {
                            $query->where('id_bodega', $idBodega);
                        }
                    })
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'zona' => 'Zona',
                        'pasillo' => 'Pasillo',
                        'estante' => 'Estante',
                        'nivel' => 'Nivel',
                        'posicion' => 'Posición',
                    ])
                    ->default('zona')
                    ->required(),
                Forms\Components\TextInput::make('codigo')
                    ->label('Código')
                    ->maxLength(50),
                Forms\Components\Toggle::make('estado')
                    ->label('Activo')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bodega.nombre')
                    ->label('Bodega')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'zona' => 'info',
                        'pasillo' => 'warning',
                        'estante' => 'success',
                        'nivel' => 'danger',
                        'posicion' => 'primary',
                    }),
                Tables\Columns\TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable(),
                Tables\Columns\IconColumn::make('estado')
                    ->label('Activo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo')
                    ->options([
                        'zona' => 'Zona',
                        'pasillo' => 'Pasillo',
                        'estante' => 'Estante',
                        'nivel' => 'Nivel',
                        'posicion' => 'Posición',
                    ]),
                Tables\Filters\TernaryFilter::make('estado')
                    ->label('Activo'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->modal()
                    ->label('Nueva Ubicación')
                    ->successNotificationTitle('Ubicación creada correctamente'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUbicaciones::route('/'),
            'create' => Pages\CreateUbicacion::route('/create'),
            'edit' => Pages\EditUbicacion::route('/{record}/edit'),
        ];
    }
}