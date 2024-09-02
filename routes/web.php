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