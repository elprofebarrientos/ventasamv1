<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductoResource\Pages;
use App\Filament\Resources\ProductoResource\RelationManagers;
use App\Models\Producto;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductoResource extends Resource
{
    protected static ?string $model = Producto::class;

    protected static ?string $navigationLabel = 'Productos';

    protected static ?string $label = 'Producto';

    protected static ?string $pluralLabel = 'Productos';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-s-cube';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, $set) => $set('slug', \Str::slug($state))),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique('productos', 'slug', ignoreRecord: true),
                Forms\Components\Textarea::make('descripcion')
                    ->label('Descripción')
                    ->maxLength(65535)
                    ->rows(4),
                Forms\Components\Select::make('id_categoria')
                    ->label('Categoría')
                    ->options(fn () => \App\Models\Categoria::where('activo', true)->pluck('nombre', 'id_categoria'))
                    ->nullable()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('id_marca')
                    ->label('Marca')
                    ->relationship('marca', 'nombre')
                    ->nullable()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('id_unidad_medida')
                    ->label('Unidad de Medida')
                    ->relationship('unidadMedida', 'nombre')
                    ->nullable()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('estado')
                    ->label('Estado')
                    ->options([
                        'disponible' => 'Disponible',
                        'no_disponible' => 'No Disponible',
                        'descontinuado' => 'Descontinuado',
                    ])
                    ->required()
                    ->default('disponible'),
                Forms\Components\Toggle::make('permite_venta')
                    ->label('Permite Venta')
                    ->default(true),
                Forms\Components\Toggle::make('permite_alquiler')
                    ->label('Permite Alquiler')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_producto')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('categoria.nombre')
                    ->label('Categoría')
                    ->searchable(),
                Tables\Columns\TextColumn::make('marca.nombre')
                    ->label('Marca')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->colors([
                        'success' => 'disponible',
                        'warning' => 'no_disponible',
                        'danger' => 'descontinuado',
                    ]),
                Tables\Columns\BooleanColumn::make('permite_venta')
                    ->label('Venta'),
                Tables\Columns\BooleanColumn::make('permite_alquiler')
                    ->label('Alquiler'),
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
                        'disponible' => 'Disponible',
                        'no_disponible' => 'No Disponible',
                        'descontinuado' => 'Descontinuado',
                    ]),
                Tables\Filters\Filter::make('permite_venta')
                    ->label('Solo permite venta')
                    ->query(fn ($query) => $query->where('permite_venta', true)),
                Tables\Filters\Filter::make('permite_alquiler')
                    ->label('Solo permite alquiler')
                    ->query(fn ($query) => $query->where('permite_alquiler', true)),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make()
                    ->label('Agregar Variante'),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->headerActions([
                CreateAction::make()
                    ->modal()
                    ->label('Nuevo Producto'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\VariantesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductos::route('/'),
            'create' => Pages\CreateProducto::route('/create'),
            'edit' => Pages\EditProducto::route('/{record}/edit'),
        ];
    }
}
