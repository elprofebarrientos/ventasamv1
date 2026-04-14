<?php

namespace App\Filament\Resources\VarianteResource\Pages;

use App\Filament\Resources\VarianteResource;
use App\Models\Atributo;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateVariante extends CreateRecord
{
    protected static string $resource = VarianteResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();
        
        \Illuminate\Support\Facades\Log::info('Data before create: ', $data);
        
        $atributosSeleccionados = $data['atributos_seleccionados'] ?? [];
        \Illuminate\Support\Facades\Log::info('Atributos seleccionados: ', $atributosSeleccionados);
        
        unset($data['atributos_seleccionados']);
        
        $data['_atributos'] = $atributosSeleccionados;
        
        return $data;
    }

    protected function afterCreate(): void
    {
        $formState = $this->form->getState();
        $atributos = $formState['atributos_seleccionados'] ?? [];
        $idProducto = $formState['id_producto'] ?? null;
        
        \Illuminate\Support\Facades\Log::info('Atributos from form state: ', $atributos);
        \Illuminate\Support\Facades\Log::info('Record ID: ', ['id' => $this->record->id_variante]);
        
        if (empty($atributos)) {
            return;
        }
        
        $valoresIds = [];
        foreach ($atributos as $atributo) {
            if (isset($atributo['id_valor'])) {
                $valoresIds[] = $atributo['id_valor'];
            }
        }
        
        sort($valoresIds);
        
        $variantesExistentes = \App\Models\ProductoVariante::where('id_producto', $idProducto)
            ->with('valores')
            ->get();
        
        foreach ($variantesExistentes as $variante) {
            $valoresExistentes = $variante->valores->pluck('id_valor')->toArray();
            sort($valoresExistentes);
            
            if ($valoresExistentes == $valoresIds) {
                $this->record->forceDelete();
                Notification::make()
                    ->title('Error')
                    ->body('Ya existe una variante para este producto con los mismos atributos y valores.')
                    ->danger()
                    ->send();
                $this->halt();
            }
        }
        
        $atributosIds = [];
        foreach ($atributos as $atributo) {
            if (isset($atributo['id_atributo'])) {
                $atributosIds[] = $atributo['id_atributo'];
            }
        }
        
        $duplicados = array_diff_assoc($atributosIds, array_unique($atributosIds));
        
        if (!empty($duplicados)) {
            $nombresDuplicados = Atributo::whereIn('id_atributo', array_unique($duplicados))->pluck('nombre')->toArray();
            $this->record->forceDelete();
            Notification::make()
                ->title('Error')
                ->body('No se puede seleccionar el mismo atributo dos veces: ' . implode(', ', $nombresDuplicados))
                ->danger()
                ->send();
            $this->halt();
        }
        
        $valoresIdsForDuplicates = [];
        foreach ($atributos as $atributo) {
            if (isset($atributo['id_valor'])) {
                $valoresIdsForDuplicates[] = $atributo['id_valor'];
            }
        }
        
        if (count($valoresIdsForDuplicates) !== count(array_unique($valoresIdsForDuplicates))) {
            $this->record->forceDelete();
            Notification::make()
                ->title('Error')
                ->body('No se puede seleccionar el mismo valor de atributo dos veces.')
                ->danger()
                ->send();
            $this->halt();
        }
        
        $attachData = [];
        foreach ($atributos as $atributo) {
            if (isset($atributo['id_valor'])) {
                $attachData[$atributo['id_valor']] = [
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        
        \Illuminate\Support\Facades\Log::info('Attach data: ', $attachData);
        
        if (!empty($attachData)) {
            $this->record->valores()->syncWithoutDetaching($attachData);
            \Illuminate\Support\Facades\Log::info('Attach executed');
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Variante guardada';
    }
}