<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VarianteResource\Pages;
use App\Models\Atributo;
use App\Models\ProductoVariante;
use App\Rules\UniqueAtributoRule;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class VarianteResource extends Resource
{
    protected static ?string $model = ProductoVariante::class;

    protected static \BackedEnum | string | null $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationLabel = 'Variantes';

    protected static string | UnitEnum | null $navigationGroup = 'Productos';

    protected static ?string $modelLabel = 'Variante';

    protected static ?string $pluralModelLabel = 'Variantes';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Forms\Components\Select::make('id_producto')
                    ->label('Producto')
                    ->relationship('producto', 'nombre')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('sku')
                    ->label('SKU')
                    ->maxLength(100),
                Forms\Components\TextInput::make('codigo_barras')
                    ->label('Código de Barras')
                    ->maxLength(100),
                Forms\Components\Toggle::make('tiene_lote')
                    ->label('Tiene Lote')
                    ->default(false),
                Forms\Components\Toggle::make('tiene_fecha_vencimiento')
                    ->label('Tiene Fecha de Vencimiento')
                    ->default(false),
                Forms\Components\Toggle::make('activo')
                    ->label('Activo')
                    ->default(true),
                Forms\Components\Repeater::make('atributos_seleccionados')
                    ->label('Atributos y Valores')
                    ->columnSpan(2)
                    ->columns(2)
                    ->rules([new UniqueAtributoRule()])
                    ->schema([
                        Forms\Components\Select::make('id_atributo')
                            ->label('Atributo')
                            ->options(\App\Models\Atributo::where('estado', 1)->pluck('nombre', 'id_atributo'))
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->required()
                            ->afterStateUpdated(function ($state, $set) {
                                $set('id_valor', null);
                            }),
                        Forms\Components\Select::make('id_valor')
                            ->label('Valor')
                            ->options(function ($get) {
                                $idAtributo = $get('id_atributo');
                                if (!$idAtributo) {
                                    return [];
                                }
                                return \App\Models\AtributoValor::where('id_atributo', $idAtributo)
                                    ->where('estado', 1)
                                    ->pluck('valor', 'id_valor');
                            })
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->required(false),
                Forms\Components\Repeater::make('imagenes_json')
                    ->label('Imágenes')
                    ->columnSpan(2)
                    ->schema([
                        Forms\Components\FileUpload::make('url')
                            ->label('Imagen')
                            ->image()
                            ->directory('productos/variantes')
                            ->visibility('public')
                            ->required(),
                        Forms\Components\Toggle::make('principal')
                            ->label('Principal')
                            ->default(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('producto.nombre')
                    ->label('Producto')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                Tables\Columns\TextColumn::make('codigo_barras')
                    ->label('Código de Barras')
                    ->searchable(),
                Tables\Columns\TextColumn::make('atributos_y_valores')
                    ->label('Atributos')
                    ->getStateUsing(function ($record) {
                        return $record->valores->map(function ($valor) {
                            return $valor->atributo->nombre . ': ' . $valor->valor;
                        })->join(' | ');
                    })
                    ->badge()
                    ->color('info')
                    ->searchable(),
                Tables\Columns\IconColumn::make('tiene_lote')
                    ->label('Tiene Lote')
                    ->boolean(),
                Tables\Columns\IconColumn::make('tiene_fecha_vencimiento')
                    ->label('Tiene Vencimiento')
                    ->boolean(),
                Tables\Columns\IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('id_producto')
                    ->label('Producto')
                    ->relationship('producto', 'nombre'),
                Tables\Filters\SelectFilter::make('activo')
                    ->label('Estado')
                    ->options([
                        true => 'Activo',
                        false => 'Inactivo',
                    ]),
            ])
            ->modifyQueryUsing(function ($query) {
                return $query->with(['valores.atributo', 'producto']);
            })
            ->actions([
                EditAction::make()
                    ->label('Editar'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Eliminar Seleccionados'),
                ]),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Nueva Variante'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVariantes::route('/'),
            'create' => Pages\CreateVariante::route('/create'),
            'edit' => Pages\EditVariante::route('/{record}/edit'),
        ];
    }
}