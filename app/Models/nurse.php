<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nurse extends Model
{
    /** @use HasFactory<\Database\Factories\NurseFactory> */
    use HasFactory;

    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'suffix',
        'date_of_birth',
        'sex',
        'phone_number',
        'address',
        
    ];
}
