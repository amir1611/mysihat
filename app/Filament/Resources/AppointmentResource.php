<?php

namespace App\Filament\Resources;

use App\Enums\AppointmentStatusEnum;
use App\Filament\Resources\AppointmentResource\Pages;
use App\Models\Appointment;
use App\Models\TimeSlot;
use App\Models\User;
use Faker\Provider\ar_EG\Text;
use Filament\Actions\Action;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\View;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Container\Attributes\Log;
use Ramsey\Uuid\Type\Time;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $label = 'Appointments';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([

                    Wizard\Step::make('Appointment Details')
                        ->columns(2)
                        ->schema(self::getAppointmentDetails()),
                    Wizard\Step::make('Medical Information')

                        ->columns(2)
                        ->schema(self::getMedicalInformation()),
                    Wizard\Step::make('Emergency Contact')
                        ->columns(2)
                        ->schema(self::getEmergencyContact()),
                ])->columnSpanFull(),

            ]);
    }

    public static function getAppointmentDetails(): array
    {
        return [
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
                ->formatStateUsing(function ($get) {
                    return User::find($get('doctor_id'))->name;
                }),
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
                })->visibleOn('create'),
            ToggleButtons::make('appointment_time')
                ->label('Appointment Time')
                ->required()
                ->inline()
                ->columnSpanFull()
                ->afterStateUpdated(function ($state, callable $set, $get) {

                    $set('appointment_time', $state);
                })
                ->options(function ($get, $set) {
                    // dd($get('date'));
                    $selectedDate = $get('date'); // Get the selected client_id

                    if ($selectedDate) {
                        $timeSlots = TimeSlot::where('date', $selectedDate)->pluck('time_slot', 'id');

                        $formattedTimeSlots = $timeSlots->sort()->map(function ($time) {
                            return \Carbon\Carbon::createFromFormat('H:i:s', $time)->format('h:i A'); // 24-hour format
                        });
                        return $formattedTimeSlots;
                    }

                    return $get('appointment_time') != null ? [\Carbon\Carbon::createFromFormat('H:i:s', $get('appointment_time'))->format('h:i A')] : [];
                })->visibleOn('edit'),
            ToggleButtons::make('appointment_time')
                ->label('Appointment Time')
                ->required()
                ->inline()
                ->columnSpanFull()
                ->options(fn($get) => [$get('appointment_time') => \Carbon\Carbon::createFromFormat('H:i:s', $get('appointment_time'))->format('h:i A')])
                ->visibleOn('view'),
        ];
    }

    public static function getMedicalInformation(): array
    {
        return [
            Textarea::make('reason')
                ->label('Reason for Appointment')
                ->required()
                ->rows(3)
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
        ];
    }

    public static function getEmergencyContact(): array
    {
        return [
            TextInput::make('emergency_contact_name')
                ->label('Emergency Contact Name')
                ->required()
                ->columnSpanFull(),

            TextInput::make('emergency_contact_number')
                ->label('Emergency Contact Number')
                ->required()
                ->tel()
                ->prefix('+60'),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('patient_id')
                //     ->sortable(),

                Tables\Columns\TextColumn::make('doctor_id')
                    ->label('Doctor Name')
                    ->formatStateUsing(function ($state) {
                        return User::find($state)->name;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('appointment_date')
                    ->searchable()
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('appointment_time')
                    ->formatStateUsing(function ($state) {
                        return \Carbon\Carbon::createFromFormat('H:i:s', $state)->format('h:i A');
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'pending' => 'Pending',
                            'approved' => 'Approved',
                            'rejected' => 'Rejected',
                            'cancelled' => 'Cancelled',
                            default => $state,
                        };
                    })
                    ->badge()
                    ->color(function ($state) {
                        return match ($state) {
                            'pending' => 'warning',
                            'approved' => 'success',
                            'rejected' => 'danger',
                            'cancelled' => 'danger',
                            default => 'danger',
                        };
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // By on status
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'cancelled' => 'Cancelled',
                    ])
                    ->native(false)
                    ->label('Status'),

                // Based on date

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'view' => Pages\ViewAppointment::route('/{record}'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }

    public static function displayDoctorList(): array
    {

        return User::role(['doctor'])->where('name', '!=', 'Admin')->get()->map(function ($doctor) {

            return [

                View::make('html-content')->view('filament.custom-components.select-modal', ['doctor' => $doctor])->columnSpanFull(),
            ];
        })->flatten()->toArray();
    }

    protected function displayDoctorList2()
    {
        $doctor = User::role(['doctor'])->where('name', '!=', 'Admin')->get();
        return $doctor;
    }

    public function testClose()
    {
        return $this->dispatch('open-modal', id: 'doctor-modal');
    }
}
