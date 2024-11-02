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
            Stat::make('Total Appointments', auth()->user()->role === 'patient' ? Appointment::where('patient_id', auth()->id())->count() : Appointment::where('doctor_id', auth()->id())->count()),

        ];
    }
}
