<?php

namespace App\Filament\Resources\EmpresaResource\Pages;

use App\Filament\Resources\EmpresaResource;
use App\Models\Empresa;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Redirect;

class CreateEmpresa extends CreateRecord
{
    protected static string $resource = EmpresaResource::class;

    public function mount(): void
    {
        if (Empresa::exists()) {
            $this->redirect(EmpresaResource::getUrl('index'));
        }

        parent::mount();
    }
}
