<?php

namespace App\Filament\Resources;

use App\Models\VariantePrecio;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Livewire\Component;
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
                    ->relationship('variante', 'sku', fn ($query) => $query->with(['producto', 'valores.atributo']))
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->producto->nombre . ' - ' . $record->valores->map(fn ($vv) => $vv->atributo->nombre . ': ' . $vv->valor)->join(', '))
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn ($state, $set) => $set('precio_base', \App\Models\VariantePrecio::getUltimoCosto((int) $state)))
                    ->afterStateHydrated(fn ($state, $set, $record) => $set('precio_base_original', $record?->precio_base ?? 0))
                    ->unique(table: 'variante_precio', column: 'variante_id', ignoreRecord: true),
                TextInput::make('precio_base_original')
                    ->hidden(),
                TextInput::make('margen_porcentaje_original')
                    ->hidden(),
                TextInput::make('hay_cambios')
                    ->hidden(),
                TextInput::make('precio_base')
                    ->label('Precio Base')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->live()
                    ->suffixAction(
                        Action::make('buscarCosto')
                            ->label('Buscar Último Costo')
                            ->icon('heroicon-m-magnifying-glass')
                            ->action(function (Get $get, Set $set) {
                                $varianteId = $get('variante_id');
                                if ($varianteId) {
                                    $ultimoCosto = \App\Models\VariantePrecio::getUltimoCosto((int) $varianteId);
                                    if ($ultimoCosto !== null) {
                                        $set('precio_base', $ultimoCosto);
                                    }
                                }
                            })
                    )
                    ->afterStateUpdated(function ($state, $set, $get, Component $livewire) {
                        $base = (float) ($state ?? 0);
                        $margen = (float) ($get('margen_porcentaje') ?? 0);
                        $set('precio_final', round($base * (1 + $margen / 100), 2));
                        
                        $record = $livewire->getRecord();
                        $precioBaseOriginal = $record ? (float) $record->precio_base : 0;
                        $margenOriginal = $record ? (float) $record->margen_porcentaje : 0;
                        
                        if ($precioBaseOriginal > 0 && ($base != $precioBaseOriginal || $margen != $margenOriginal)) {
                            $set('hay_cambios', true);
                        }
                    })
                    ->afterStateHydrated(function ($state, $set, $get) {
                        $base = (float) ($state ?? 0);
                        $margen = (float) ($get('margen_porcentaje') ?? 0);
                        $set('precio_final', round($base * (1 + $margen / 100), 2));
                    })
                    ->helperText(fn ($get) => $get('variante_id') ? 'Último costo de compra: $' . number_format(\App\Models\VariantePrecio::getUltimoCosto((int) $get('variante_id')) ?? 0, 0, ',', '.') : null),
                TextInput::make('margen_porcentaje')
                    ->label('Margen (%)')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->live()
                    ->afterStateHydrated(function ($state, $set, $get) {
                        $base = (float) ($get('precio_base') ?? 0);
                        $margen = (float) ($state ?? 0);
                        $set('precio_final', round($base * (1 + $margen / 100), 2));
                    })
                    ->afterStateUpdated(function ($state, $set, $get, Component $livewire) {
                        $base = (float) ($get('precio_base') ?? 0);
                        $margen = (float) ($state ?? 0);
                        $set('precio_final', round($base * (1 + $margen / 100), 2));
                        
                        $record = $livewire->getRecord();
                        $precioBaseOriginal = $record ? (float) $record->precio_base : 0;
                        $margenOriginal = $record ? (float) $record->margen_porcentaje : 0;
                        
                        if ($precioBaseOriginal > 0 && ($base != $precioBaseOriginal || $margen != $margenOriginal)) {
                            $set('hay_cambios', true);
                        }
                    }),
                TextInput::make('precio_final')
                    ->label('Precio Final')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->live()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $precioFinal = (float) ($state ?? 0);
                        $impuestoIds = $get('impuesto_ids') ?? [];
                        $totalImpuestos = 0;
                        if (!empty($impuestoIds)) {
                            $impuestos = \App\Models\Impuesto::whereIn('id', $impuestoIds)->where('activo', true)->get();
                            foreach ($impuestos as $impuesto) {
                                if ($impuesto->tipo === 'porcentaje') {
                                    $totalImpuestos += $precioFinal * $impuesto->valor / 100;
                                } else {
                                    $totalImpuestos += $impuesto->valor;
                                }
                            }
                        }
                        $set('precio_venta', round($precioFinal + $totalImpuestos, 2));
                    })
                    ->suffixAction(
                        Action::make('actualizarPrecioVenta')
                            ->label('Actualizar Precio Venta')
                            ->visible(fn (Get $get) => (bool) $get('hay_cambios'))
                            ->button()
                            ->color('success')
                            ->icon('heroicon-m-currency-dollar')
                            ->action(function (Get $get, Set $set) {
                                $precioFinal = (float) ($get('precio_final') ?? 0);
                                $impuestoIds = $get('impuesto_ids') ?? [];
                                $totalImpuestos = 0;
                                if (!empty($impuestoIds)) {
                                    $impuestos = \App\Models\Impuesto::whereIn('id', $impuestoIds)->where('activo', true)->get();
                                    foreach ($impuestos as $impuesto) {
                                        if ($impuesto->tipo === 'porcentaje') {
                                            $totalImpuestos += $precioFinal * $impuesto->valor / 100;
                                        } else {
                                            $totalImpuestos += $impuesto->valor;
                                        }
                                    }
                                }
$set('precio_venta', round($precioFinal + $totalImpuestos, 2));
                                $set('hay_cambios', false);
                                Notification::make()
                                    ->title('Precio de Venta actualizado')
                                    ->body('El precio de venta ha sido actualizado con el nuevo precio final.')
                                    ->success()
                                    ->send();
                            })
                    ),
                Select::make('impuesto_ids')
                    ->label('Impuestos')
                    ->multiple()
                    ->live()
                    ->options(\App\Models\Impuesto::where('activo', true)->get()->mapWithKeys(fn ($i) => [$i->id => $i->nombre . ' (' . ($i->tipo === 'porcentaje' ? $i->valor . '%' : '$' . number_format($i->valor, 0, ',', '.')) . ')']))
                    ->helperText('Seleccione los impuestos que aplican a esta variante')
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $precioFinal = (float) ($get('precio_final') ?? 0);
                        $impuestoIds = $state ?? [];
                        $totalImpuestos = 0;
                        if (!empty($impuestoIds)) {
                            $impuestos = \App\Models\Impuesto::whereIn('id', $impuestoIds)->where('activo', true)->get();
                            foreach ($impuestos as $impuesto) {
                                if ($impuesto->tipo === 'porcentaje') {
                                    $totalImpuestos += $precioFinal * $impuesto->valor / 100;
                                } else {
                                    $totalImpuestos += $impuesto->valor;
                                }
                            }
                        }
                        $set('precio_venta', $precioFinal + $totalImpuestos);
                    }),
                TextInput::make('precio_venta')
                    ->label('Precio de Venta')
                    ->helperText('Precio con impuestos')
                    ->disabled()
                    ->formatStateUsing(fn ($state) => $state ? number_format((float) $state, 0, ',', '.') : null),
                TextInput::make('mostrar_alerta')
                    ->label('')
                    ->hidden(),
                DateTimePicker::make('fecha_actualizacion')
                    ->label('Fecha de Actualización'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('variante.producto.nombre')
                    ->label('Variante')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($state, $record) => $state . ' - ' . $record->variante->valores->map(fn ($v) => $v->atributo->nombre . ': ' . $v->valor)->join(', ')),
                Tables\Columns\TextColumn::make('precio_base')
                    ->label('Último Costo')
                    ->sortable()
                    ->money('COP'),
                Tables\Columns\TextColumn::make('precio_final')
                    ->label('Precio Final')
                    ->sortable()
                    ->money('COP'),
                Tables\Columns\TextColumn::make('impuesto_ids')
                    ->label('Impuestos')
                    ->formatStateUsing(fn ($state, $record) => implode(', ', collect($state ?? [])->map(fn ($id) => \App\Models\Impuesto::find($id)?->nombre ?? '')->filter()->toArray())),
                Tables\Columns\TextColumn::make('precio_venta')
                    ->label('Precio Venta')
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
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
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