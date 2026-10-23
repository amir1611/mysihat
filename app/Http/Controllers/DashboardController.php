<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\BloodSugarLevel;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function patient()
    {
        $user = auth()->user();
        $appointments = Appointment::with('doctor')
            ->where('patient_id', $user->id)
            ->orderBy('appointment_time')
            ->take(4)
            ->get();

        return view('dashboard.patient', [
            'appointments' => $appointments,
            'recordCount' => DB::table('medical_records')->where('user_id', $user->id)->count(),
            'bloodSugarCount' => BloodSugarLevel::where('user_id', $user->id)->count(),
        ]);
    }

    public function doctor()
    {
        $this->ensureDoctor();
        $user = auth()->user();
        $appointments = Appointment::with('patient')
            ->where('doctor_id', $user->id)
            ->where('appointment_time', '>=', now()->startOfDay())
            ->orderBy('appointment_time')
            ->take(6)
            ->get();

        return view('dashboard.doctor', [
            'appointments' => $appointments,
            'todayCount' => Appointment::where('doctor_id', $user->id)->whereDate('appointment_time', today())->count(),
            'patientCount' => Appointment::where('doctor_id', $user->id)->distinct('patient_id')->count('patient_id'),
        ]);
    }

    public function doctorAppointments()
    {
        $this->ensureDoctor();

        return view('dashboard.doctor-appointments', [
            'appointments' => Appointment::with('patient')
                ->where('doctor_id', auth()->id())
                ->orderBy('appointment_time')
                ->get(),
        ]);
    }

    public function admin()
    {
        $this->ensureAdmin();

        return view('dashboard.admin', [
            'patientCount' => User::where('type', 0)->count(),
            'doctorCount' => User::where('type', 2)->count(),
            'appointmentCount' => Appointment::count(),
            'todayAppointments' => Appointment::whereDate('appointment_time', today())->count(),
            'recentUsers' => User::latest()->take(6)->get(),
        ]);
    }

    public function adminUsers()
    {
        $this->ensureAdmin();

        return view('dashboard.admin-users', [
            'users' => User::latest()->paginate(12),
        ]);
    }

    public function adminAppointments()
    {
        $this->ensureAdmin();

        return view('dashboard.admin-appointments', [
            'appointments' => Appointment::with(['patient', 'doctor'])->latest('appointment_time')->paginate(12),
        ]);
    }

    public function records()
    {
        return view('dashboard.records', [
            'records' => DB::table('medical_records')->where('user_id', auth()->id())->latest()->get(),
        ]);
    }

    public function careTeam()
    {
        return view('dashboard.care-team', [
            'doctors' => User::where('type', 2)->orderBy('expertise')->get(['id', 'name', 'expertise', 'phone_number']),
        ]);
    }

    private function ensureAdmin(): void
    {
        abort_unless(auth()->user()?->type === 1, 403);
    }

    private function ensureDoctor(): void
    {
        abort_unless(auth()->user()?->type === 2, 403);
    }
}
