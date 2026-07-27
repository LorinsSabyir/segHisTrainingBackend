<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class patient extends Model
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
        'address',
        
    ];
}
