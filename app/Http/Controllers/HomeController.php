<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function patientHomepage()
    {
        if (Auth::user()->type !== 0) {
            abort(403);
        }
        return view('patient.patient-homepage');
    }

    public function adminHomepage()
    {
        if (Auth::user()->type !== 1) {
            abort(403);
        }
        return view('admin.admin-homepage');
    }

    public function doctorHomepage()
    {
        if (Auth::user()->type !== 2) {
            abort(403);
        }
        return view('doctor.doctor-homepage');
    }
}
