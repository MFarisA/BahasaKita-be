<?php

namespace App\Filament\Resources\CulturalContentResource\Pages;

use App\Filament\Resources\CulturalContentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCulturalContent extends EditRecord
{
    protected static string $resource = CulturalContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
