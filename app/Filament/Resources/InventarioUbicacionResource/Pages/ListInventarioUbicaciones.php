<?php

namespace App\Filament\Resources\InventarioUbicacionResource\Pages;

use App\Filament\Resources\InventarioUbicacionResource;
use App\Models\InventarioUbicacion;
use Filament\Resources\Pages\ListRecords;

class ListInventarioUbicaciones extends ListRecords
{
    protected static string $resource = InventarioUbicacionResource::class;

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'stock_actual';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    protected function modifyQueryUsing($query)
    {
        return $query->with(['variante.producto', 'variante.valores.atributo', 'ubicacion.bodega']);
    }
}