<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
  
    public function __construct()
    {
        $this->middleware('auth');
    }
  

    public function patientHomepage(): View
    {
        return view('patient.patient-homepage');
    } 
  
  
    public function adminHomepage(): View
    {
        return view('admin.admin-homepage');
    }
  

    public function doctorHomepage(): View
    {
        return view('doctor.doctor-homepage');
    }
}
