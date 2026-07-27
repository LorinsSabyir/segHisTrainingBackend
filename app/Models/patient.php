<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    /** @use HasFactory<\Database\Factories\PatientFactory> */
    use HasFactory;

    protected $fillable = [
        'phn',
        'case_number',
        'consultation_date',
        'consultation_time',
        'first_name',
        'last_name',
        'suffix',
        'date_of_birth',
        'sex',
        'blood_group',
        'time_of_arrival',
        'phone_number',
        'address_street',
        'address_brgy',
        'address_city',
        'address_province',
        'nurse_id',
        'doctor_id',
    ];

    public function nurse()
    {
        return $this->belongsTo(User::class, 'nurse_id');
    }
    
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
