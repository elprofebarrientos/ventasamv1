<?php

namespace App\Filament\Resources\CodigoClienteResource\Pages;

use App\Filament\Resources\CodigoClienteResource;
use Filament\Resources\Pages\EditRecord;

class EditCodigoCliente extends EditRecord
{
    protected static string $resource = CodigoClienteResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
