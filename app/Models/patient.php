<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    /** @use HasFactory<\Database\Factories\PatientFactory> */
    use HasFactory;

    protected $fillable = [
        'pid',
        'dateRegistered',
        'name_first',
        'name_last',
        'name_middle',
        'name_suffix',
        'sex',
        'phone_number',
        'blood_group',
        'date_of_birth',
        'age',
        'civil_status',
        'place_of_birth',
        'religion',
        'ethnicity',
        'address_street',
        'address_brgy',
        'address_city',
        'address_province',
        'address_country',
        'address_zipcode',
        'patient_mother_name',
        'patient_father_name',
        'patient_guardian_name',
        'patient_guardian_relationship',
        'patient_spouse_name',
        'nurse_id',
    ];

    public function nurseLog()
    {
        return $this->hasMany(User::class, 'nurse_id');
    }

    public function patientEncounter()
    {
        return $this->hasMany(PatientEncounter::class, 'patient_id');
    }

}
