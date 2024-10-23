<?php

namespace App\Http\Controllers\Appointment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function appointmentListPage()
    {
        return view('appointment.appointment_list');
    }

    public function appointmentCreatePage()
    {
        // Fetch doctors from the users table where type = 1
        $doctors = User::where('type', 2)->get(['id', 'name']);

        // Extract unique departments
       // $departments = $doctors->pluck('department')->unique();

        return view('appointment.appointment_create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'medical_records' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png',
            'current_medications' => 'nullable|string',
        ]);

        $appointment = new Appointment();
        $appointment->patient_id = Auth::id();
        $appointment->doctor_id = $request->doctor_id;
        $appointment->appointment_time = $request->appointment_date . ' ' . $request->appointment_time;
        $appointment->current_medications = $request->current_medications;

        if ($request->hasFile('medical_records')) {
            $path = $request->file('medical_records')->store('medical_records', 'public');
            $appointment->medical_conditions_record = $path;
        }

        $appointment->save();

        return response()->json(['message' => 'Appointment booked successfully']);
    }
   
}

