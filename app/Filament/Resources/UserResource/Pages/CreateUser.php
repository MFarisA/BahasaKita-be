<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;


class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected array $profileData = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['profile'])) {
            $this->profileData = $data['profile'];
            unset($data['profile']);
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        if (!empty($this->profileData)) {
            $this->record->profile()->create($this->profileData);
            $this->record->load('profile');
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
