<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Alignment;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;
    protected static bool $canCreateAnother = false;

    public function getFormActionsAlignment(): string|Alignment
    {
        return Alignment::End;
    }
    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
