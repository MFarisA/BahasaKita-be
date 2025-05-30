<?php

namespace App\Filament\Resources\SubUnitResource\Pages;

use App\Filament\Resources\SubUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubUnits extends ListRecords
{
    protected static string $resource = SubUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
