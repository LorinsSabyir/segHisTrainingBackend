<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    /** @use HasFactory<\Database\Factories\WardFactory> */
    use HasFactory;

    protected $fillable = [
        'ward_id',
        'description',
        'dept_nr'
    ];

    public function patientEncounter()
    {
        return $this->hasMany(PatientEncounter::class, 'ward_id');
    }
}
