<?php

namespace App\Filament\Resources\VariantePrecioResource\Pages;

use App\Filament\Resources\VariantePrecioResource;
use App\Models\VariantePrecio;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateVariantePrecio extends CreateRecord
{
    protected static string $resource = VariantePrecioResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $varianteId = $data['variante_id'] ?? null;
        
        if ($varianteId) {
            $existing = VariantePrecio::where('variante_id', $varianteId)->first();
            
            if ($existing) {
                Notification::make()
                    ->title('Registro existente')
                    ->body('Ya existe un precio para esta variante. Se abrirá el formulario de edición.')
                    ->warning()
                    ->send();
                
                redirect()->to(static::getResource()::getUrl('edit', ['record' => $existing->getKey()]));
            }
        }
        
        return $data;
    }
}