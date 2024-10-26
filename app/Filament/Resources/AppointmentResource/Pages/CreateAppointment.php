<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use App\Models\TimeSlot;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Wizard\Step;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateAppointment extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = AppointmentResource::class;

    protected $doctorID;

    //mount
    public function mount(): void
    {
        $this->authorizeAccess();
        $this->doctorID = request()->query('doctor');
        $this->fillForm(
            [
                'doctor_id' => $this->doctorID,
            ]
        );
    }

    protected function getSteps(): array
    {
        return [
            Step::make('Appointment Details')
                ->columns(2)
                ->schema([
                    Hidden::make('patient_id')
                        ->default(auth()->id()),
                    // Select::make('doctor_id')
                    //     ->label('Select Doctor')
                    //     ->required()
                    //     ->preload()
                    //     ->options(function () {
                    //         return User::role(['doctor'])->where('name', '!=', 'Admin')->get()->pluck('name', 'id');
                    //     })
                    //     ->searchable(),
                    TextInput::make('doctor_id')
                        ->label('Select Doctor')
                        ->required()
                        ->disabled()
                        ->columnSpan(1)
                        ->default(fn() => User::find($this->doctorID)->name),

                    // View::make('html-content')->view('filament.custom-components.select-modal', [
                    //     'doctors' => User::role(['doctor'])->where('name', '!=', 'Admin')->get(),
                    // ])->columnSpanFull(),

                    // Actions::make([
                    //     Actions\Action::make('Select Doctor')
                    //         ->label('Select Doctor')
                    //         ->modalSubmitAction(false)
                    //         ->modalCancelAction(false)
                    //         ->livewireClickHandlerEnabled(true)
                    //         ->extraAttributes([
                    //             // 'wire:click' => '',
                    //         ])

                    //         ->form([
                    //             Grid::make()
                    //                 ->columns(2)
                    //                 ->schema(
                    //                     self::displayDoctorList()
                    //                 )
                    //         ])
                    //         ->visible(fn() => !request()->has('doctor_id')),
                    // ])->columnSpanFull(),

                    DatePicker::make('appointment_date')
                        ->label('Appointment Date')
                        ->required()
                        ->native(false)
                        ->minDate(now())
                        ->closeOnDateSelection()
                        ->columnSpan(1)
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, $get) {

                            $set('date', $state);
                        }),

                    // Select::make('appointment_time')
                    //     ->label('Appointment Time')
                    //     ->required()
                    //     ->options(function ($get) {
                    //         $selectedDate = $get('appointment_date'); // Get the selected client_id
                    //         if ($selectedDate) {
                    //             // Fetch branches that belong to the selected client
                    //             return TimeSlot::where('date', $selectedDate)->pluck('time_slot', 'id')->toArray();
                    //         }

                    //         return [];
                    //     }),

                    ToggleButtons::make('appointment_time')
                        ->label('Appointment Time')
                        ->required()
                        ->inline()
                        ->columnSpanFull()
                        ->options(function ($get, $set) {

                            $selectedDate = $get('date');

                            if ($selectedDate) {
                                $timeSlots = TimeSlot::where('date', $selectedDate)->pluck('time_slot', 'id');

                                $formattedTimeSlots = $timeSlots->sort()->map(function ($time) {
                                    return \Carbon\Carbon::createFromFormat('H:i:s', $time)->format('h:i A'); // 24-hour format
                                });

                                return $formattedTimeSlots;
                            }

                            return [];
                        }),


                ]),

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
