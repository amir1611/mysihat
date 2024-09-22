<?php

namespace App\Http\Controllers\Booking;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getBookingList()
    {
        return view('booking.booking-list');
    }

    
}
