<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;

// Redirect the root URL to the login page
Route::get('/', function () {
    return redirect('login');
});

// Route for the admin login form
Route::get('/admin', [LoginController::class, 'showAdminLoginForm'])->name('admin.login');

// Default authentication routes for users
Auth::routes();

// User-specific routes with middleware
Route::middleware(['auth', 'user-access:patient'])->group(function () {
    Route::get('/home', [HomeController::class, 'patientHomepage'])->name('patient.homepage');
});

// Admin-specific routes with middleware
Route::middleware(['auth', 'user-access:admin'])->group(function () {
    Route::get('/admin/home', [HomeController::class, 'adminHomepage'])->name('admin.homepage');
});

// Doctor-specific routes with middleware
Route::middleware(['auth', 'user-access:doctor'])->group(function () {
    Route::get('/doctor/homepage', [HomeController::class, 'doctorHomepage'])->name('doctor.home');
});

// Patient dashboard route
Route::get('/patient/dashboard', function () {
    return view('patient.patient-homepage');
})->name('patient.dashboard')->middleware('auth');

// Doctor dashboard route
Route::get('/doctor/dashboard', function () {
    return view('doctor.doctor-homepage');
})->name('doctor.dashboard')->middleware('auth');

// Admin dashboard route
Route::get('/admin/dashboard', function () {
    return view('admin.admin-homepage');
})->name('admin.dashboard')->middleware('auth');

// Login route
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Home route
Route::get('/home', function () {
    return view('home');
})->name('home');
