<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User; // Ensure the User model is imported

class UserController extends Controller
{
    // Show the admin's profile
    public function editProfile()
    {
        $user = Auth::user();
        return view('manageProfile.admin.admin-profile', compact('user'));
    }

    // Update the admin's profile
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone_number' => 'nullable|string|max:15',
        ]);

        $user = Auth::user();
        if (!$user instanceof User) {
            return redirect()->route('admin.profile')->with('error', 'User not found.');
        }

        $user->fill($request->only('name', 'email', 'phone_number'));
        $user->save();
        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully.');
    }

    // New method for doctor profile edit
    public function editDoctorProfile()
    {
        $user = Auth::user(); // Get the currently authenticated doctor
        return view('manageProfile.doctor.doctor-profile', compact('user'));
    }

    // New method for updating doctor profile
    public function updateDoctorProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'nullable|string|max:15',
            'gender' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
        ]);

        $user = Auth::user();
        if (!$user instanceof User) {
            return redirect()->route('doctor.profile')->with('error', 'User not found.');
        }

        $user->fill($request->only('name', 'email', 'phone_number', 'gender', 'date_of_birth'));
        $user->save();
        return redirect()->route('doctor.profile')->with('success', 'Profile updated successfully.');
    }

    // New method for patient profile edit
    public function editPatientProfile()
    {
        $user = Auth::user();
        return view('manageProfile.patient.patient-profile', compact('user'));
    }

    // New method for updating patient profile
    public function updatePatientProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'nullable|string|max:15',
            'gender' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
        ]);

        $user = Auth::user();
        if (!$user instanceof User) {
            return redirect()->route('patient.profile')->with('error', 'User not found.');
        }

        $user->fill($request->only('name', 'email', 'phone_number', 'gender', 'date_of_birth'));
        $user->save();

        return redirect()->route('patient.profile')->with('success', 'Profile updated successfully.');
    }
}
