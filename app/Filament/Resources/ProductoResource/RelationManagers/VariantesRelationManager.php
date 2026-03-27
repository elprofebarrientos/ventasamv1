<?php

namespace App\Filament\Resources\ProductoResource\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use App\Models\Atributo;
use App\Models\AtributoValor;

class VariantesRelationManager extends RelationManager
{
    protected static string $relationship = 'variantes';

    protected static ?string $title = 'Variantes';

    protected static ?string $recordTitleAttribute = 'sku';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Forms\Components\Hidden::make('id_producto')
                    ->default(fn () => $this->ownerRecord->id_producto),
                Forms\Components\TextInput::make('sku')
                    ->label('SKU')
                    ->maxLength(100)
                    ->columnSpan(1),
                Forms\Components\TextInput::make('codigo_barras')
                    ->label('Código de Barras')
                    ->maxLength(100)
                    ->columnSpan(1),
                Forms\Components\Toggle::make('activo')
                    ->label('Activo')
                    ->default(true)
                    ->columnSpan(1),
                Forms\Components\Repeater::make('imagenes_json')
                    ->label('Imágenes')
                    ->columnSpan(3)
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
                        Forms\Components\TextInput::make('order')
                            ->label('Orden')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(3),
                Forms\Components\Repeater::make('atributos_valores')
                    ->label('Atributos y Valores')
                    ->columnSpan(3)
                    ->schema([
                        Forms\Components\Select::make('id_atributo')
                            ->label('Atributo')
                            ->options(function (callable $get, $livewire) {
                                $atributosValores = $livewire->data['atributos_valores'] ?? [];
                                $usedAtributos = collect($atributosValores)
                                    ->pluck('id_atributo')
                                    ->filter()
                                    ->toArray();
                                return Atributo::whereNotIn('id_atributo', $usedAtributos)->pluck('nombre', 'id_atributo');
                            })
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set) => $set('id_valor', null)),
                        Forms\Components\Select::make('id_valor')
                            ->label('Valor')
                            ->options(function (callable $get) {
                                $idAtributo = $get('id_atributo');
                                if (!$idAtributo) {
                                    return [];
                                }
                                return AtributoValor::where('id_atributo', $idAtributo)
                                    ->pluck('valor', 'id_valor');
                            })
                            ->required()
                            ->disabled(fn (callable $get) => !$get('id_atributo')),
                    ])
                    ->columns(2)
                    ->addActionLabel('Agregar Atributo')
                    ->deleteAction(fn ($action) => $action->requiresConfirmation())
                    ->rules([
                        function () {
                            return function (string $attribute, $value, \Closure $fail) {
                                if (!is_array($value)) {
                                    return;
                                }
                                
                                $seenAtributos = [];
                                $seenValores = [];
                                foreach ($value as $item) {
                                    if (!isset($item['id_atributo']) || !isset($item['id_valor'])) {
                                        continue;
                                    }
                                    
                                    // Check for duplicate atributo
                                    if (in_array($item['id_atributo'], $seenAtributos)) {
                                        $fail('No se permiten atributos duplicados.');
                                        return;
                                    }
                                    $seenAtributos[] = $item['id_atributo'];
                                    
                                    // Check for duplicate valor
                                    if (in_array($item['id_valor'], $seenValores)) {
                                        $fail('No se permiten valores duplicados en los atributos.');
                                        return;
                                    }
                                    $seenValores[] = $item['id_valor'];
                                }
                            };
                        },
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_variante')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('codigo_barras')
                    ->label('Código de Barras')
                    ->searchable(),
                Tables\Columns\IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('valores.atributo.nombre')
                    ->label('Atributos')
                    ->badge()
                    ->separator(','),
                Tables\Columns\TextColumn::make('valores.valor')
                    ->label('Valores')
                    ->badge()
                    ->separator(','),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Actions\CreateAction::make()
                    ->label('Nueva Variante')
                    ->after(function (array $data, $record) {
                        $atributosValores = $data['atributos_valores'] ?? [];
                        $existingValorIds = $record->valores()->pluck('atributo_valor.id_valor')->toArray();
                        foreach ($atributosValores as $item) {
                            if (isset($item['id_valor']) && !in_array($item['id_valor'], $existingValorIds)) {
                                $record->valores()->attach($item['id_valor']);
                            }
                        }
                    }),
            ])
            ->actions([
                Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data, $record): array {
                        $data['atributos_valores'] = $record->valores->map(function ($valor) {
                            return [
                                'id_atributo' => $valor->id_atributo,
                                'id_valor' => $valor->id_valor,
                            ];
                        })->toArray();
                        return $data;
                    })
                    ->after(function (array $data, $record) {
                        $atributosValores = $data['atributos_valores'] ?? [];
                        $syncData = [];
                        $seen = [];
                        foreach ($atributosValores as $item) {
                            if (isset($item['id_valor']) && !in_array($item['id_valor'], $seen)) {
                                $syncData[] = $item['id_valor'];
                                $seen[] = $item['id_valor'];
                            }
                        }
                        $record->valores()->syncWithoutDetaching($syncData);
                    }),
                Actions\DeleteAction::make(),
                Actions\ForceDeleteAction::make(),
                Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                    Actions\ForceDeleteBulkAction::make(),
                    Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }
}
