<?php

namespace App\Filament\Resources\AtributoResource\Pages;

use App\Filament\Resources\AtributoResource;
use Filament\Resources\Pages\EditRecord;

class EditAtributo extends EditRecord
{
    protected static string $resource = AtributoResource::class;

    protected function getUpdatedNotificationTitle(): ?string
    {
        return 'Guardado correctamente';
    }
}
