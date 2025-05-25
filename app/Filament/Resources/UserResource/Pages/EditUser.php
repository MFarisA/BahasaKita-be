<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $profile = $this->record->profile;
        
        if ($profile) {
            $data['profile'] = [
                'level' => $profile->level,
                'xp' => $profile->xp,
                'photo' => $profile->photo,
            ];
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $profileData = [];
        
        if (isset($data['profile'])) {
            $profileData = $data['profile'];
            unset($data['profile']);
        }
        
        if (!empty($profileData)) {
            if ($this->record->profile) {
                $this->record->profile->update($profileData);
            } else {
                $this->record->profile()->create($profileData);
            }
        }

        return $data;
    }
}