<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class nurse extends Model
{
    /** @use HasFactory<\Database\Factories\NurseFactory> */
    use HasFactory;

    protected $fillable = [
        'case_number',
        'consultation_date',
        'consultation_time',
        'family_name',
        'given_name',
        'suffix',
        'date_of_birth',
        'sex',
        'blood_group',
        'time_of_arrival',
        
    ];
}
