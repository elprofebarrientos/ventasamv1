<?php

namespace App\Filament\Resources\RecepcionResource\Pages;

use App\Filament\Resources\RecepcionResource;
use Filament\Resources\Pages\Page;

class ListRecepciones extends Page
{
    protected static string $resource = RecepcionResource::class;

    protected string $view = 'filament.resources.recepcion-resource.pages.list-recepciones';
}