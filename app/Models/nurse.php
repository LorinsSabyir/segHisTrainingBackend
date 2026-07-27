<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class nurse extends Model
{
    /** @use HasFactory<\Database\Factories\NurseFactory> */
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'suffix',
        'date_of_birth',
        'sex',
        'phone_number',
        'address',
        
    ];
}
