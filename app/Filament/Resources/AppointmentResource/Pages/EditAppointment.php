<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use Filament\Actions;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\EditRecord\Concerns\HasWizard;

class EditAppointment extends EditRecord
{
    use HasWizard;

    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSteps(): array
    {
        return [
            Step::make('Appointment Details')
                ->columns(2)
                ->schema(AppointmentResource::getAppointmentDetails()),

            Step::make('Medical Information')
                ->schema(AppointmentResource::getMedicalInformation()),
            Step::make('Emergency Contact')
                ->schema(AppointmentResource::getEmergencyContact()),
        ];
    }
}
