<?php

namespace App\Filament\Resources\RecepcionHistorial\Pages;

use App\Filament\Resources\RecepcionHistorial;
use App\Models\RecepcionCompra;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Collection;

class ListRecepcionHistorial extends Page
{
    protected static string $resource = RecepcionHistorial::class;

    protected string $view = 'filament.resources.recepcion-historial.pages.list-recepcion-historial';

    public Collection $recepciones;

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
        $this->recepciones = RecepcionCompra::with(['compra.proveedor', 'detalles.variante.producto'])
            ->whereHas('compra', function ($q) {
                $q->where('resultado_recepcion', 'Completa');
            })
            ->orderBy('fecha', $this->sortOrder)
            ->get();
    }
}