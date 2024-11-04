<?php

namespace Database\Seeders;

use App\Models\TimeSlot;
use DateTime;
use Illuminate\Database\Seeder;

class TimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generate time slots for doctor IDs from 4 to 67 and dates from 2024-11-06 to 2024-11-30
        $timeSlots = ['11:00:00', '13:00:00', '14:00:00', '15:00:00', '16:00:00'];
        $startDate = new DateTime('2024-11-06');
        $endDate = new DateTime('2024-11-30');

        foreach (range(4, 67) as $doctorId) {
            $currentDate = clone $startDate;
            while ($currentDate <= $endDate) {
                foreach ($timeSlots as $slot) {
                    TimeSlot::create([
                        'doctor_id' => $doctorId,
                        'date' => $currentDate->format('Y-m-d'),
                        'time_slot' => $slot,
                        'status' => 'available',
                    ]);
                }
                $currentDate->modify('+1 day');
            }
        }

    }
}
