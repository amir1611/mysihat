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
        $appointments = Appointment::query()
            ->with('doctor:id,name,expertise')
            ->where('patient_id', Auth::id())
            ->orderByDesc('appointment_time')
            ->get();

        return view('appointment.appointment_list', compact('appointments'));
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
            'doctor_id' => 'nullable|exists:users,id',
            'selected_doctor' => 'nullable|exists:users,id',
            'appointment_date' => 'nullable|date',
            'appointment_time' => 'nullable',
            'reason' => 'nullable|string|max:2000',
            'medical_records' => 'nullable|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png',
            'medical_conditions_record' => 'nullable|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png',
            'current_medications' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_number' => 'nullable|string|max:50',
        ]);

        $doctorId = $request->input('doctor_id', $request->input('selected_doctor'));
        $appointmentDate = $request->input('appointment_date', $request->input('appointment_time'));

        if (! $doctorId || ! $appointmentDate) {
            return response()->json(['message' => 'Please select a doctor and appointment date.'], 422);
        }

        $appointment = new Appointment();
        $appointment->patient_id = Auth::id();
        $appointment->doctor_id = $doctorId;
        $appointment->reason = $request->input('reason', 'General consultation');
        $time = $request->input('appointment_time_value') ?: $request->input('appointment_time', '09:00');
        $appointment->appointment_time = $appointmentDate . ' ' . $time;
        $appointment->current_medications = $request->current_medications;
        $appointment->emergency_contact_name = $request->input('emergency_contact_name', '');
        $appointment->emergency_contact_number = $request->input('emergency_contact_number', '');

        $recordFile = $request->file('medical_records', $request->file('medical_conditions_record'));

        if ($recordFile) {
            $path = $recordFile->store('medical_records', 'public');
            $appointment->medical_conditions_record = $path;
        }

        $appointment->save();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Appointment booked successfully']);
        }

        return redirect()->route('appointmentList')->with('success', 'Appointment booked successfully.');
    }
   
}

