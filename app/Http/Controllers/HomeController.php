<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function patientHomepage()
    {
        return view('manageProfile.patient.patient-dashboard');
    }

    public function doctorHomepage()
    {
        return view('manageProfile.doctor.doctor-dashboard');
    }

    public function adminHomepage()
    {
        return view('manageProfile.admin.admin-dashboard');
    }
}
