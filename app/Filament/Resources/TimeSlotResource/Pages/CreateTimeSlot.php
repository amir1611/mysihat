<?php

namespace App\Filament\Resources\TimeSlotResource\Pages;

use App\Filament\Resources\TimeSlotResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTimeSlot extends CreateRecord
{
    protected static string $resource = TimeSlotResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
