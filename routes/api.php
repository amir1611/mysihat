<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimeSlotController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/time-slots', [TimeSlotController::class, 'index']); // Get all time slots
        Route::post('/time-slots', [TimeSlotController::class, 'store']); // Create a new time slot
        Route::put('/time-slots/{id}', [TimeSlotController::class, 'update']); // Update an existing time slot
        Route::delete('/time-slots/{id}', [TimeSlotController::class, 'destroy']); // Delete a time slot




