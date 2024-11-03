<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use App\Http\Controllers\ZoomController;
use App\Models\MedicalRecord;
use App\Models\TimeSlot;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Wizard\Step;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Jubaer\Zoom\Facades\Zoom;

class CreateAppointment extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = AppointmentResource::class;

    protected $doctorID;

    protected $summaryID;

    //mount
    public function mount(): void
    {
        $this->authorizeAccess();
        $this->doctorID = request()->query('doctor');

        if (request()->query('summaryId')) {
            //   dd(request()->query('summaryId'));
            $this->summaryID = request()->query('summaryId');

        }

        $this->fillForm(
            [
                'ss' => $this->doctorID,
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

                    Hidden::make('doctor_id')
                        ->default($this->doctorID),
                    TextInput::make('ss')
                        ->label('Select Doctor')
                        ->disabled()
                        ->columnSpan(1)
                        ->reactive()
                        ->default(fn () => User::find($this->doctorID)->name),

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
                                // dd($this->doctorID);
                                $timeSlots = TimeSlot::where('doctor_id', $get('doctor_id'))->where('date', $selectedDate)->where('status', 'available')->pluck('time_slot', 'id');

                                $formattedTimeSlots = $timeSlots->sort()->map(function ($time) {
                                    return \Carbon\Carbon::createFromFormat('H:i:s', $time)->format('h:i A'); // 24-hour format
                                });

                                return $formattedTimeSlots;
                            }

                            return [];
                        }),

                ]),

            Step::make('Medical Information')
                ->schema([
                    Textarea::make('reason')
                        ->label('Reason for Appointment')
                        ->required()
                        ->rows(3)
                        ->default($this->summaryID != null ? MedicalRecord::find($this->summaryID)->summary : '')
                        ->columnSpanFull(),

                    Textarea::make('current_medications')
                        ->label('Current Medications (Optional)')
                        ->rows(2)
                        ->columnSpanFull(),

                    FileUpload::make('medical_conditions_record')
                        ->label('Upload Medical Records (Optional)')
                        ->directory('medical_records')
                        ->openable(true)
                        ->panelAspectRatio('4:3')
                        ->panelLayout('integrated'),
                ]),
            Step::make('Emergency Contact')
                ->schema(AppointmentResource::getEmergencyContact()),
        ];
    }

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {

    //     $time = TimeSlot::find($data['appointment_time']);
    //     //  dd($time['time_slot']);
    //     $data['appointment_time'] = $time['time_slot'];
    //     //$data['doctor_id'] = request()->query('doctor');

    //     dd($data);
    //     return $data;
    // }

    protected function handleRecordCreation(array $data): Model
    {
        // $meetings = Zoom::createMeeting([
        //     'agenda' => 'Appointment with '.User::find($data['doctor_id'])->name,
        //     'topic' => 'Appointment with '.User::find($data['doctor_id'])->name,
        //     'type' => 2, // 1 => instant, 2 => scheduled, 3 => recurring with no fixed time, 8 => recurring with fixed time
        //     'duration' => 60, // in minutes
        //     'timezone' => 'Asia/Kuala_Lumpur', // set your timezone
        //     'password' => '1234',
        //     //  'start_time' => '', // set your start time
        //     // 'template_id' => 'set your template id', // set your template id  Ex: "Dv4YdINdTk+Z5RToadh5ug==" from https://marketplace.zoom.us/docs/api-reference/zoom-api/meetings/meetingtemplates
        //     'pre_schedule' => true,  // set true if you want to create a pre-scheduled meeting
        //     //   'schedule_for' => 'set your schedule for profile email ', // set your schedule for
        //     'settings' => [
        //         'join_before_host' => false, // if you want to join before host set true otherwise set false
        //         'host_video' => false, // if you want to start video when host join set true otherwise set false
        //         'participant_video' => false, // if you want to start video when participants join set true otherwise set false
        //         'mute_upon_entry' => false, // if you want to mute participants when they join the meeting set true otherwise set false
        //         'waiting_room' => false, // if you want to use waiting room for participants set true otherwise set false
        //         'audio' => 'both', // values are 'both', 'telephony', 'voip'. default is both.
        //         'auto_recording' => 'none', // values are 'none', 'local', 'cloud'. default is none.
        //         'approval_type' => 0, // 0 => Automatically Approve, 1 => Manually Approve, 2 => No Registration Required
        //     ],

        // ]);
        // dd($meetings);
        $data['appointment_time'] = TimeSlot::find($data['appointment_time'])['time_slot'];
        $data['status'] = 'pending';
        $data['google_meeting_link'] = $this->createGoogleMeeting();

        return parent::handleRecordCreation($data);
    }

    // after create
    public function afterCreate(): void
    {
        // $zoom = new ZoomController;
        // $meeting = $zoom->scheduleMeeting();
        // dd($meeting);
        TimeSlot::find($this->data['appointment_time'])->update(['status' => 'booked']);
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

    private function createGoogleMeeting(): string
    {
        $links = [
            'https://meet.google.com/cit-qiug-fsc',
            'https://meet.google.com/gnm-cesd-vry',
            'https://meet.google.com/xfz-gdmu-mmz',
            'https://meet.google.com/qeu-efug-mjm',
            'https://meet.google.com/cxd-kwum-zuj',
            'https://meet.google.com/grs-suqz-bne',
            'https://meet.google.com/vus-ogrr-xww',
            'https://meet.google.com/zxm-vtce-ihz',
            'https://meet.google.com/mgy-rwin-zuu',
            'https://meet.google.com/wfq-sgtj-osq',
            'https://meet.google.com/mgd-umwr-tme',
        ];

        $randomLink = $links[array_rand($links)];

        return $randomLink;
    }
}
