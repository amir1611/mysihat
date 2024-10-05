<?php

namespace App\Http\Controllers;

use App\Models\TimeSlot; // Make sure to create a TimeSlot model
use Illuminate\Http\Request;

class TimeSlotController extends Controller
{
    // Get all time slots
    public function index()
    {
        $timeSlots = TimeSlot::all();
        return response()->json(['success' => true, 'slots' => $timeSlots]);
    }

    // Store a new time slot
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'timeSlots' => 'required|array',
            'timeSlots.*' => 'required|string', // Validate each time slot
        ]);

        $timeSlot = TimeSlot::create([
            'date' => $request->date,
            'time_slots' => json_encode($request->timeSlots), // Store time slots as JSON
        ]);

        return response()->json(['success' => true, 'slot' => $timeSlot]);
    }

    // Update an existing time slot
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'timeSlots' => 'required|array',
            'timeSlots.*' => 'required|string', // Validate each time slot
        ]);

        $timeSlot = TimeSlot::findOrFail($id);
        $timeSlot->update([
            'date' => $request->date,
            'time_slots' => json_encode($request->timeSlots), // Update time slots as JSON
        ]);

        return response()->json(['success' => true]);
    }

    // Delete a time slot
    public function destroy($id)
    {
        $timeSlot = TimeSlot::findOrFail($id);
        $timeSlot->delete();

        return response()->json(['success' => true]);
    }
}
