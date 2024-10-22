<?php

namespace App\Http\Controllers;

use App\Models\TimeSlot; // Make sure to create a TimeSlot model
use Illuminate\Container\Attributes\Auth;
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
        
        try {
            // Validate the request
            $request->validate([
                'date' => 'required|date',
                'timeSlots' => 'required|array',
                'timeSlots.*' => 'required|string', // Validate each time slot
            ]);
    
            // Check if any of the provided time slots already exist for the given date
            foreach ($request->timeSlots as $timeSlot) {
                if (TimeSlot::where('date', $request->date)->where('time_slot', $timeSlot)->exists()) {
                    return response()->json(['success' => false, 'message' => "Time slot $timeSlot already exists for this date"], 400);
                }
            }
    
            // Save each time slot individually
            $savedSlots = [];
            foreach ($request->timeSlots as $timeSlot) {
                dd($request->all());
                $savedSlots[] = TimeSlot::create([
                    'doctor_id' => 2,
                    'date' => $request->date,
                    'time_slot' => $timeSlot,
                ]);
            }
    
            return response()->json(['success' => true, 'slots' => $savedSlots]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
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
