<?php

namespace App\Filament\Resources\BodegaResource\Pages;

use App\Filament\Resources\BodegaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBodega extends CreateRecord
{
    protected static string $resource = BodegaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}