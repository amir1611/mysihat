<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    // Specify the table name if it doesn't follow Laravel's naming convention
    protected $table = 'appointments';

    // Define the fillable attributes
    protected $fillable = [
        'id',
        'patient_id',
        'doctor_id',
        'reason',
        'medical_conditions_record',
        'current_medications',
        'appointment_time',
        'emergency_contact_name',
        'emergency_contact_number',
    ];

    // Define relationships if needed
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}