<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimeSlotController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/time-slots', [TimeSlotController::class, 'index']); // Get all time slots
        Route::post('/time-slots', [TimeSlotController::class, 'store']); // Create a new time slot
        Route::put('/time-slots/{id}', [TimeSlotController::class, 'update']); // Update an existing time slot
        Route::delete('/time-slots/{id}', [TimeSlotController::class, 'destroy']); // Delete a time slot

