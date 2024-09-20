<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;

// Redirect the root URL to the home page
Route::get('/', function () {
    return view('auth.home');
})->name('auth.home');

// Route for the admin login form
Route::get('/admin', function () {
    return view('auth.admin-login');
})->name('admin.login');

// Default authentication routes for users
Auth::routes();

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Patient routes
    Route::prefix('patient')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'patientHomepage'])->name('patient.dashboard');
        
        // Route for patient profile
        Route::get('/profile', [UserController::class, 'editPatientProfile'])->name('patient.profile');
        Route::post('/profile', [UserController::class, 'updatePatientProfile'])->name('patient.profile.update');
    });

    // Doctor routes
    Route::prefix('doctor')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'doctorHomepage'])->name('doctor.dashboard');
        
        // Route for doctor profile
        Route::get('/profile', [UserController::class, 'editDoctorProfile'])->name('doctor.profile');
        Route::post('/profile', [UserController::class, 'updateDoctorProfile'])->name('doctor.profile.update');
    });

    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'adminHomepage'])->name('admin.dashboard');
        // Add more admin-specific routes here

        // Route for the admin profile
        Route::get('/profile', [UserController::class, 'editProfile'])->name('admin.profile');
        Route::post('/profile', [UserController::class, 'updateProfile'])->name('admin.profile.update');
    });
});
