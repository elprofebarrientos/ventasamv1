<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecepcionResource\Pages;
use App\Models\RecepcionCompra;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class RecepcionResource extends Resource
{
    protected static ?string $model = RecepcionCompra::class;

    protected static \BackedEnum | string | null $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationLabel = 'Recepción Compras';

    protected static ?string $modelLabel = 'Recepción';

    protected static ?string $pluralModelLabel = 'Recepciones';

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
            'index' => Pages\ListRecepciones::route('/'),
        ];
    }
}