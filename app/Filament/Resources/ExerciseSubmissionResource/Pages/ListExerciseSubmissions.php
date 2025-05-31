<?php

namespace App\Filament\Resources\ExerciseSubmissionResource\Pages;

use App\Filament\Resources\ExerciseSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExerciseSubmissions extends ListRecords
{
    protected static string $resource = ExerciseSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
