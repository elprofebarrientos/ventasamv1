<?php

namespace App\Filament\Resources\InventarioDisponibleResource\Pages;

use App\Filament\Resources\InventarioDisponibleResource;
use Filament\Resources\Pages\Page;

class ListInventarioDisponible extends Page
{
    protected static string $resource = InventarioDisponibleResource::class;

    protected string $view = 'filament.resources.inventario-disponible.pages.list-inventario-disponible';
}