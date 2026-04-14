<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CodigoUsoResource\Pages;
use App\Models\CodigoUso;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class CodigoUsoResource extends Resource
{
    protected static ?string $model = CodigoUso::class;

    protected static ?string $navigationLabel = 'Códigos Usados';

    protected static string | UnitEnum | null $navigationGroup = 'Configurar';

    protected static ?string $label = 'Código Usado';

    protected static ?string $pluralLabel = 'Códigos Usados';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-s-check-circle';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('codigo_cliente_id')
                    ->label('Código')
                    ->relationship('codigoCliente', 'codigo')
                    ->required(),
                Forms\Components\TextInput::make('venta_id')
                    ->label('Venta ID')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('cliente_id')
                    ->label('Cliente ID')
                    ->numeric(),
                DateTimePicker::make('fecha_uso')
                    ->label('Fecha de Uso'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('codigoCliente.codigo')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('venta_id')
                    ->label('Venta ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cliente_id')
                    ->label('Cliente ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_uso')
                    ->label('Fecha de Uso')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('fecha_uso')
                    ->label('Por fecha')
                    ->form([
                        DateTimePicker::make('fecha_inicio'),
                        DateTimePicker::make('fecha_fin'),
                    ])
                    ->query(fn ($query, $data) => $query
                        ->when($data['fecha_inicio'] ?? null, fn ($q, $date) => $q->where('fecha_uso', '>=', $date))
                        ->when($data['fecha_fin'] ?? null, fn ($q, $date) => $q->where('fecha_uso', '<=', $date))
                    ),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCodigoUsos::route('/'),
        ];
    }
}