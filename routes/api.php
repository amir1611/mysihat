<?php

use App\Http\Controllers\TimeSlotController;
use App\Http\Controllers\ZoomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/time-slots', [TimeSlotController::class, 'index']); // Get all time slots
Route::post('/time-slots', [TimeSlotController::class, 'store']); // Create a new time slot
Route::put('/time-slots/{id}', [TimeSlotController::class, 'update']); // Update an existing time slot
Route::delete('/time-slots/{id}', [TimeSlotController::class, 'destroy']); // Delete a time slot

Route::get('/test', function () {
    return response()->json(['message' => 'Hello World!']);
});

Route::post('/zoom/create-meeting', [ZoomController::class, 'createMeeting']);
