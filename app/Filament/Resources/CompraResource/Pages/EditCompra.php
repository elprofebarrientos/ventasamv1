<?php

namespace App\Filament\Resources\CompraResource\Pages;

use App\Filament\Resources\CompraResource;
use App\Models\Compra;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompra extends EditRecord
{
    protected static string $resource = CompraResource::class;

    protected function getUpdatedNotificationTitle(): ?string
    {
        return 'Guardado correctamente';
    }

    protected function getHeaderActions(): array
    {
        $record = $this->getRecord();
        
        return [
            Actions\DeleteAction::make()
                ->visible(fn () => $record->abonos()->count() === 0 && $record->resultado_recepcion !== 'Completa'),
        ];
    }
}
