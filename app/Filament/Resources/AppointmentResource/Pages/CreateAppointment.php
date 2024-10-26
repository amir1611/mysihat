<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use App\Models\TimeSlot;
use Filament\Forms\Components\Wizard\Step;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateAppointment extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = AppointmentResource::class;

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

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $time = TimeSlot::find($data['appointment_time']);
        //  dd($time['time_slot']);
        $data['appointment_time'] = $time['time_slot'];

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        $title = 'Appointment created successfully!';

        if (blank($title)) {
            return null;
        }

        return Notification::make()
            ->success()
            ->title($title);
    }
}
