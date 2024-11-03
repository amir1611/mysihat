<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewAppointment extends ViewRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->color('edit')
                ->visible(fn ($record) => auth()->user()->hasRole('patient')),
            Actions\Action::make('join_meeting')->label('Join Meeting')->url(fn () => $this->record->google_meeting_link)->color('success'),

            Actions\Action::make('Cancel Appointment')
                ->requiresConfirmation()
                ->action(fn () => $this->cancelAppointment())
                ->color('danger')
                ->visible(fn ($record) => $record->status !== 'cancelled' && auth()->user()->hasRole('patient')),

            Actions\Action::make('View Patient')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Close')
                ->form(fn ($record) => [

                    FileUpload::make('avatar_url')
                        ->hiddenLabel(true)
                        ->default($record->patient->avatar_url)
                        ->disabled()
                        ->avatar()
                        ->image()
                        ->columnSpanFull(),

                    Grid::make()
                        ->columns(3)
                        ->schema([
                            Placeholder::make('patient_name')
                                ->content($record->patient->name),

                            Placeholder::make('patient_email')
                                ->content($record->patient->email),
                            Placeholder::make('patient_phone')
                                ->content($record->patient->phone_number),
                        ]),

                    Grid::make()
                        ->columns(3)
                        ->schema([

                            Placeholder::make('date_of_birth')
                                ->content(Carbon::parse($record->patient->date_of_birth)->format('d M Y')),

                            Placeholder::make('patient_gender')
                                ->content($record->patient->gender),

                            Placeholder::make('patient_ic_number')
                                ->content($record->patient->ic_number),
                        ]),

                ])
                ->color('gray')
                ->visible(fn ($record) => auth()->user()->hasRole('doctor')),
        ];
    }

    public function cancelAppointment()
    {
        $this->record->update(['status' => 'cancelled']);

        Notification::make()->title('Appointment Cancelled')->success();

        return redirect()->route('filament.patient.resources.appointments.index');
    }
}
