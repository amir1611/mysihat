<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Stats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Appointments', $this->getAppointmentCount())
                ->color(auth()->user()->role === 'patient' ? 'info' : 'gray'),

        ];
    }

    public function getAppointmentCount(): int
    {
        if (auth()->user()->hasRole('patient')) {
            return Appointment::where('patient_id', auth()->id())->count();
        }

        return Appointment::where('doctor_id', auth()->id())->count();
    }
}
