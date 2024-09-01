<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return redirect('auth.login');
});

Auth::routes();

Route::middleware(['auth', 'user-access:patient'])->group(function () {
    Route::get('/home', [HomeController::class, 'patientHomepage'])->name('patient.homepage');
});
  

Route::middleware(['auth', 'user-access:admin'])->group(function () {
  
    Route::get('/admin/home', [HomeController::class, 'adminHomepage'])->name('admin.homepage');
});
  

Route::middleware(['auth', 'user-access:doctor'])->group(function () {
  
    Route::get('/doctor/homepage', [HomeController::class, 'doctorHomepage'])->name('doctor.home');
});


