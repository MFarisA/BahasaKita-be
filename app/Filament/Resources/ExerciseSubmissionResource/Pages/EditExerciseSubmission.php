<?php

namespace App\Filament\Resources\ExerciseSubmissionResource\Pages;

use App\Filament\Resources\ExerciseSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExerciseSubmission extends EditRecord
{
    protected static string $resource = ExerciseSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
