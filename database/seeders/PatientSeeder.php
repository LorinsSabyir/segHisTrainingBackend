<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Patient;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Patient::create([
            'pid' => '000001',
            'date_registered' => now(),
            'name_first' => 'Fate John',
            'name_last' => 'Delos Reyes',
            'name_middle' => 'A',
            'name_suffix' => 'Jr.',
            'sex' => 'Male',
            'phone_number' => '09123456789',
            'blood_group' => 'O+',
            'date_of_birth' => '2003-12-03',
            'age' => 20,
            'civil_status' => 'Single',
            'place_of_birth' => 'Davao City',
            'religion' => 'Christianity',
            'ethnicity' => 'Filipino',
            'address_street' => '123 Main St',
            'address_brgy' => 'Barangay 1',
            'address_city' => 'Davao City',
            'address_province' => 'Davao del Sur',
            'address_country' => 'Philippines',
            'address_zipcode' => '8000',
            'patient_mother_name' => 'Maria Delos Reyes',
            'patient_father_name' => 'Juan Delos Reyes',
            'patient_guardian_name' => null,
            'patient_guardian_relationship' => null,
            'patient_spouse_name' => null,

        ]);
    }
}
