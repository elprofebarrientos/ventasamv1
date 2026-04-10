<?php

namespace App\Filament\Resources\CodigoClienteResource\Pages;

use App\Filament\Resources\CodigoClienteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCodigoCliente extends CreateRecord
{
    protected static string $resource = CodigoClienteResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
