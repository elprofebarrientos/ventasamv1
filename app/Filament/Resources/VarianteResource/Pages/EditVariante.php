<?php

namespace App\Filament\Resources\VarianteResource\Pages;

use App\Filament\Resources\VarianteResource;
use App\Models\Atributo;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditVariante extends EditRecord
{
    protected static string $resource = VarianteResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $variante = $this->getRecord();
        
        $atributosData = [];
        if ($variante->valores) {
            foreach ($variante->valores as $valor) {
                $atributosData[] = [
                    'id_atributo' => $valor->id_atributo,
                    'id_valor' => $valor->id_valor,
                ];
            }
        }
        
        $data['atributos_seleccionados'] = $atributosData;
        
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_by'] = auth()->id();
        return $data;
    }

    protected function afterSave(): void
    {
        $formState = $this->form->getState();
        $atributos = $formState['atributos_seleccionados'] ?? [];
        $idProducto = $formState['id_producto'] ?? null;
        
        $valoresIds = [];
        foreach ($atributos as $atributo) {
            if (isset($atributo['id_valor'])) {
                $valoresIds[] = $atributo['id_valor'];
            }
        }
        
        sort($valoresIds);
        
        $variantesExistentes = \App\Models\ProductoVariante::where('id_producto', $idProducto)
            ->where('id_variante', '!=', $this->record->id_variante)
            ->with('valores')
            ->get();
        
        foreach ($variantesExistentes as $variante) {
            $valoresExistentes = $variante->valores->pluck('id_valor')->toArray();
            sort($valoresExistentes);
            
            if ($valoresExistentes == $valoresIds) {
                Notification::make()
                    ->title('Error')
                    ->body('Ya existe otra variante para este producto con los mismos atributos y valores.')
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
        
        $this->record->valores()->sync($attachData);
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