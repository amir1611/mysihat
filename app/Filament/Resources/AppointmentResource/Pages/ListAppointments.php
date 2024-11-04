<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use App\Models\User;
use Filament\Actions;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\View;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListAppointments extends ListRecords
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create')
                ->label('New Appointment')
                ->modalHeading('Select Doctor')
                ->modalSubmitAction(false)
                ->modalCancelAction(false)
                ->modalContent(function () {
                    $doctors = User::role(['doctor'])->where('name', '!=', 'Admin')->get();

                    return View::make('html-content')->view('filament.custom-components.select-modal', [
                        'doctors' => $doctors,
                    ])->columnSpanFull();
                })->visible(fn () => ! auth()->user()->hasRole('doctor')),

            // ->form([
            //     Grid::make()
            //         ->columnStart(1)
            //         ->schema(
            //             AppointmentResource::displayDoctorList()
            //         )->columnSpanFull()
            // ]),
        ];
    }

    public function getTabs(): array
    {
        if (auth()->user()->hasRole('super_admin')) {
            return [
                'All' => Tab::make(),
                'Pending' => Tab::make()->modifyQueryUsing(function ($query) {
                    $query->where('status', 'pending');
                }),
                'Approved' => Tab::make()->modifyQueryUsing(function ($query) {
                    $query->where('status', 'approved');
                }),
                'Cancelled' => Tab::make()->modifyQueryUsing(function ($query) {
                    $query->where('status', 'cancelled');
                }),
                'Completed' => Tab::make()->modifyQueryUsing(function ($query) {
                    $query->where('status', 'completed');
                }),
            ];
        }

        return [
        ];
    }
}
