<?php

namespace App\Filament\Resources\CulturalContentResource\Pages;

use App\Filament\Resources\CulturalContentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCulturalContents extends ListRecords
{
    protected static string $resource = CulturalContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
