<?php

namespace App\Filament\Resources\SubUnitResource\Pages;

use App\Filament\Resources\SubUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubUnit extends EditRecord
{
    protected static string $resource = SubUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
