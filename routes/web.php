<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;

// Redirect the root URL to the login page
Route::get('/', function () {
    return redirect()->route('login');
});

// Route for the admin login form
Route::get('/admin', function () {
    return view('auth.admin-login');
})->name('admin.login');

// Default authentication routes for users
Auth::routes();

// Dashboard routes with auth middleware
Route::middleware(['auth'])->group(function () {
    Route::get('/patient/dashboard', [HomeController::class, 'patientHomepage'])->name('patient.dashboard');
    Route::get('/doctor/dashboard', [HomeController::class, 'doctorHomepage'])->name('doctor.dashboard');
    Route::get('/admin/dashboard', [HomeController::class, 'adminHomepage'])->name('admin.dashboard');
});
