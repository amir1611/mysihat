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
        // Validate the request
        $request->validate([
            'reason' => 'required|string',
            'medical_conditions_record' => 'nullable|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png|max:2048',
            'current_medications' => 'nullable|string',
            'appointment_time' => 'required|date_format:Y-m-d\TH:i',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_number' => 'required|string|max:255',
            'selected_doctor' => 'required|exists:users,id',
        ]);

        // Handle file upload
        $medicalConditionsRecordPath = null;
        if ($request->hasFile('medical_conditions_record')) {
            $medicalConditionsRecordPath = $request->file('medical_conditions_record')->store('medical_records', 'public');
        }

        // Save the appointment
        $appointment = Appointment::create([
            'patient_id' => Auth::id(),
            'doctor_id' => $request->input('selected_doctor'),
            'reason' => $request->input('reason'),
            'medical_conditions_record' => $medicalConditionsRecordPath,
            'current_medications' => $request->input('current_medications'),
            'appointment_time' => $request->input('appointment_time'),
            'emergency_contact_name' => $request->input('emergency_contact_name'),
            'emergency_contact_number' => $request->input('emergency_contact_number'),
        ]);

        return redirect()->route('appointment.appointment_list')->with('success', 'Appointment booked successfully.');
    }
   
}

