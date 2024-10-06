<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    use HasFactory;

    // Specify the table name if it doesn't follow Laravel's naming convention
    protected $table = 'time_slots';

    // Specify the fillable fields
    protected $fillable = [
        'doctor_id',
        'date',
        'time_slot',
        'status',
        'appointment_id',
    ];

    // Cast the time_slots attribute to an array
    protected $casts = [
        'time_slots' => 'array',
    ];
}
