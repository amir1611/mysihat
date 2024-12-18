<?php

use App\Http\Controllers\Appointment\AppointmentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Chat\ChatbotController;
use App\Http\Controllers\Chat\StreamingChatController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TimeSlotController;
use App\Http\Controllers\BmiCalculatorController;
use App\Http\Controllers\BloodSugarCalculatorController;

// Redirect the root URL to the home page
Route::get('/', function () {
    return view('auth.home');
})->name('auth.home');

// Route for the admin login form
Route::get('/admin', function () {
    return view('auth.admin-login');
})->name('admin.login');

//Route for health article
Route::get('/health-articles', function () {
    return view('auth.health-article');
})->name('health-articles');

// Default authentication routes for users
Auth::routes();

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Common
    Route::get('/appointmentList', [AppointmentController::class, 'appointmentListPage'])->name('appointmentList');
    Route::get('/appointmentCreate', [AppointmentController::class, 'appointmentCreatePage'])->name('appointmentCreate');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');

    Route::resource('time-slots', TimeSlotController::class);


    // Patient routes
    Route::prefix('patient')->group(function () {
        // Route for patient profile
        Route::get('/profile', [UserController::class, 'editPatientProfile'])->name('patient.profile');
        Route::post('/profile', [UserController::class, 'updatePatientProfile'])->name('patient.profile.update');

        // Route for changing password
        Route::get('/change-password', [UserController::class, 'showChangePasswordForm'])->name('patient.change.password');
        Route::post('/change-password', [UserController::class, 'changePassword'])->name('patient.change.password.update');

        // Add this line to define the patient.chatbot route
        Route::get('/chatbot', [ChatbotController::class, 'index'])->name('patient.chatbot');

       //bmi-calculator
        Route::get('/bmi-calculator', [BmiCalculatorController::class, 'index'])->name('patient.bmi');
        Route::post('/bmi-calculate', [BmiCalculatorController::class, 'calculate'])->name('bmi.calculate');

        //blood-sugar-calculator
        Route::get('/blood-sugar-calculator', [BloodSugarCalculatorController::class, 'index'])->name('patient.blood-sugar');
        Route::post('/blood-sugar/analyze', [BloodSugarCalculatorController::class, 'analyze'])->name('blood-sugar.analyze');

        // Health Tools routes
        Route::get('/health-tools', function() {
            return view('patient.health-tools');
        })->name('patient.health-tools');
    });

    // Doctor routes
    Route::prefix('doctor')->group(function () {
        // Route for the doctor dashboard
        Route::get('/dashboard', [HomeController::class, 'doctorHomepage'])->name('doctor.dashboard');

        // Route for the doctor appointment time slot
        Route::get('/appointment-time-slot', [UserController::class, 'doctorAppointmentTimeSlot'])->name('doctor.appointment.time.slot');

        // Route for doctor profile
        Route::get('/profile', [UserController::class, 'editDoctorProfile'])->name('doctor.profile');
        Route::post('/profile', [UserController::class, 'updateDoctorProfile'])->name('doctor.profile.update');

        // Route for changing password
        Route::get('/change-password', [UserController::class, 'showDoctorChangePasswordForm'])->name('doctor.change.password');
        Route::post('/change-password', [UserController::class, 'changeDoctorPassword'])->name('doctor.change.password.update');
    });

    // Admin routes
    Route::prefix('admin')->group(function () {
        //Route for the admin dashboard
        Route::get('/dashboard', [HomeController::class, 'adminHomepage'])->name('admin.dashboard');


        // Route for the admin profile
        Route::get('/profile', [UserController::class, 'editProfile'])->name('admin.profile');
        Route::post('/profile', [UserController::class, 'updateProfile'])->name('admin.profile.update');

        // Route for changing password
        Route::get('/change-password', [UserController::class, 'showAdminChangePasswordForm'])->name('admin.change.password');
        Route::post('/change-password', [UserController::class, 'changeAdminPassword'])->name('admin.change.password.update');
    });

    // Chatbot routes
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
    Route::post('/render-message', [ChatbotController::class, 'render'])->name('chat.render');

    Route::get('/chat/streaming', [StreamingChatController::class, 'index']);
    Route::post('/chat/summarize', [StreamingChatController::class, 'summarizeAndStore'])->middleware('auth');

});

