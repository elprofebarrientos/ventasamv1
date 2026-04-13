<?php

namespace App\Filament\Resources\RecepcionHistorial\Pages;

use App\Filament\Resources\RecepcionHistorial;
use App\Models\Compra;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Collection;

class ListRecepcionHistorial extends Page
{
    protected static string $resource = RecepcionHistorial::class;

    protected string $view = 'filament.resources.recepcion-historial.pages.list-recepcion-historial';

    public Collection $compras;

    public string $sortOrder = 'desc';

    public function mount(): void
    {
        $this->loadData();
    }

    public function updatedSortOrder(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $this->compras = Compra::with(['proveedor', 'detalles.variante.producto'])
            ->where('resultado_recepcion', 'Completa')
            ->orderBy('created_at', $this->sortOrder)
            ->get();
    }
}