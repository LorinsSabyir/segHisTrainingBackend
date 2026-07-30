<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Http\Requests\StorepatientRequest;
use App\Http\Requests\UpdatepatientRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PatientController extends Controller implements HasMiddleware
{

    public static function middleware() {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show' ])
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Patient::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'date_registered' => 'nullable|string|max:255',
            'name_first' => 'required|string|max:255',
            'name_last' => 'required|string|max:255',
            'name_middle' => 'nullable|string|max:255',
            'name_suffix' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:11',
            'blood_group' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|string|max:255',
            'sex' => 'required|string|max:255',
            'age' => 'nullable|string|max:255',
            'civil_status' => 'nullable|string|max:255',
            'place_of_birth' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'ethnicity' => 'nullable|string|max:255',
            'address_street' => 'nullable|string|max:255',
            'address_brgy' => 'nullable|string|max:255',
            'address_city' => 'nullable|string|max:255',
            'address_province' => 'nullable|string|max:255',
            'address_country' => 'nullable|string|max:255',
            'address_zipcode' => 'nullable|string|max:255',
            'patient_mother_name' => 'nullable|string|max:255',
            'patient_father_name' => 'nullable|string|max:255',
            'patient_guardian_name' => 'nullable|string|max:255',
            'patient_guardian_relationship' => 'nullable|string|max:255',
            'patient_spouse_name' => 'nullable|string|max:255',
            
        ]);
        $lastPatient = Patient::orderByDesc('pid')->first();

        $fields['pid'] = $lastPatient ? str_pad((int)$lastPatient->pid + 1, 6, '0', STR_PAD_LEFT) : '000001';

        $patient = $request->user()->patients()->create($fields);
        // $patient = patient::create($fields);

        return $patient;
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        //
    }
}
