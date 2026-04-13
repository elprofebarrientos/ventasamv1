<?php

namespace App\Filament\Resources;

use App\Models\RecepcionCompra;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class RecepcionHistorial extends Resource
{
    protected static ?string $model = RecepcionCompra::class;

    protected static \BackedEnum | string | null $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Historial Recepciones';

    protected static ?string $modelLabel = 'Historial';

    protected static ?string $pluralModelLabel = 'Historial';

    protected static \UnitEnum | string | null $navigationGroup = 'Compras';

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return $table;
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\RecepcionHistorial\Pages\ListRecepcionHistorial::route('/'),
        ];
    }
}