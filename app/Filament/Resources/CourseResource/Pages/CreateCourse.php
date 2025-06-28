<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Alignment;

class CreateCourse extends CreateRecord
{
    protected static string $resource = CourseResource::class;
    protected static bool $canCreateAnother = false;


    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getFormActionsAlignment(): string|Alignment
    {
        return Alignment::End;
    }
}
