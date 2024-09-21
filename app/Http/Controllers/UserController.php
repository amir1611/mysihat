<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Import Hash facade
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

    // New method for showing change password form
    public function showChangePasswordForm()
    {
        return view('manageProfile.patient.patient-change-password');
    }

    // New method for handling password change
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if the user is authenticated and is an instance of User
        if (!$user instanceof User) {
            return redirect()->route('patient.profile')->with('error', 'User not found.');
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        $user->password = bcrypt($request->new_password);
        $user->save(); // This should work now

        return redirect()->route('patient.profile')->with('success', 'Password changed successfully.');
    }

    // New method for showing doctor change password form
    public function showDoctorChangePasswordForm()
    {
        return view('manageProfile.doctor.doctor-change-password'); // Ensure this view exists
    }

    // New method for handling doctor password change
    public function changeDoctorPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!$user instanceof User) {
            return redirect()->route('doctor.profile')->with('error', 'User not found.');
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return redirect()->route('doctor.profile')->with('success', 'Password changed successfully.');
    }

    // New method for showing admin change password form
    public function showAdminChangePasswordForm()
    {
        return view('manageProfile.admin.admin-change-password'); // Ensure this view exists
    }

    // New method for handling admin password change
    public function changeAdminPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!$user instanceof User) {
            return redirect()->route('admin.profile')->with('error', 'User not found.');
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Password changed successfully.');
    }
}
