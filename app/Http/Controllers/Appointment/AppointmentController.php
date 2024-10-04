<?php

namespace App\Http\Controllers\Appointment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        return view('appointment.appointment_create');
    }

   
}
