<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientEncounter extends Model
{
    /** @use HasFactory<\Database\Factories\PatientFactory> */
    use HasFactory;

    protected $fillable = [
        'case_nr',
        'encounter_date',
        'patient_type',
        'official_receipt_nr',
        'admitting_diagnosis',
        'chief_complaint',
        'is_confidential',
        'discharge_datetime',
        'iswaitlisted',
        'is_still_in',
        'consultation_date',
        'consultation_time',
        'time_of_arrival',

        'patient_id',
        'ward_id',
        'nurse_id',
        'doctor_id',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    public function nurse()
    {
        return $this->belongsTo(User::class, 'nurse_id');
    }
    
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
