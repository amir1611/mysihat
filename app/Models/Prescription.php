<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'patient_id', 'doctor_id', 'medicine_id', 'medicine_name', 'quantity', 'status', 'expires_at', 'notes',
    ];

    protected $casts = ['expires_at' => 'date'];

    public function patient() { return $this->belongsTo(User::class, 'patient_id'); }
    public function doctor() { return $this->belongsTo(User::class, 'doctor_id'); }
}
