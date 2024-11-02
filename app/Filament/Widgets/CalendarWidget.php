<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Saade\FilamentFullCalendar\Data\EventData;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    /**
     * FullCalendar will call this function whenever it needs new event data.
     * This is triggered when the user clicks prev/next or switches views on the calendar.
     */
    protected static ?int $sort = 2;

    public function fetchEvents(array $fetchInfo): array
    {
        return Appointment::query()
            ->where('appointment_date', '>=', $fetchInfo['start'])
            ->where(Auth::user()->role === 'doctor' ? 'doctor_id' : 'patient_id', Auth::id())
            ->get()
            ->map(
                fn (Appointment $event) => EventData::make()
                    ->id($event->id)
                    ->title($event->reason)
                    ->start($event->appointment_date)
                    ->end($event->appointment_date)
                //  ->toArray()
                // ->url(
                //     url: EventResource::getUrl(name: 'view', parameters: ['record' => $event]),
                //     shouldOpenUrlInNewTab: true
                // )
            )
            ->toArray();
    }
}
