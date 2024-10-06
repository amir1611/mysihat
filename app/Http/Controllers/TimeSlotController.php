<?php

namespace App\Http\Controllers;

use App\Models\TimeSlot; // Make sure to create a TimeSlot model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class TimeSlotController extends Controller
{
    public function index()
    {
        $timeSlots = TimeSlot::where('doctor_id', Auth::user()->id)->get();

        return view('appointment.appointment-time-slot', compact('timeSlots'));
    }

    public function store(Request $request)
    {

        try {
            // Insert the new time slots
            foreach ($request->time_slot as $slot) {

                TimeSlot::create([
                    'doctor_id' => Auth::user()->id,
                    'date' => $request->date,
                    'time_slot' => $slot,
                    'status' => true,
                ]);
            }

            return redirect()->back()->with('success', 'Time slots created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating time slots.');
        }
    }

    public function edit($id)
    {
        $timeSlot = TimeSlot::find($id);

        return response()->json($timeSlot);
    }

    public function update(Request $request, $id)
    {
        // Find the time slot entry by ID
        $existingSlot = TimeSlot::findOrFail($id);

        // Delete all existing time slots for the same doctor and date (you can adjust this logic based on your needs)
        TimeSlot::where('doctor_id', Auth::user()->id)
            ->where('date', $existingSlot->date)
            ->delete();

        // Insert the new time slots
        foreach ($request->time_slot as $slot) {
            TimeSlot::create([
                'doctor_id' => Auth::user()->id,  // Update doctor_id in case it was changed
                'date' => $request->date,            // Update date in case it was changed
                'time_slot' => $slot,
                'status' => true,
            ]);
        }

        return redirect()->back()->with('success', 'Time slots updated successfully.');
    }

    public function destroy($id)
    {
        TimeSlot::find($id)->delete();

        return redirect()->back()->with('success', 'Time slot deleted successfully.');
    }
}
