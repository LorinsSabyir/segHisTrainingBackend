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
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum'),
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
        try {
            $lastPatient = Patient::orderByDesc('pid')->first();
            $fields['pid'] = $lastPatient ? str_pad((int) $lastPatient->pid + 1, 6, '0', STR_PAD_LEFT) : '000001';
    
            $patient = $request->user()->patients()->create($fields);
    
            return response()->json($patient, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            \Illuminate\Support\Facades\Log::error('Failed to create patient: ' . $e->getMessage());
    
            return response()->json([
                'message' => 'Unable to save patient record. Please try again.',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        return $patient;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $fields = $request->validate([
            'date_registered' => 'nullable|string|max:255',
            'name_first' => 'sometimes|required|string|max:255',
            'name_last' => 'sometimes|required|string|max:255',
            'name_middle' => 'nullable|string|max:255',
            'name_suffix' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:11',
            'blood_group' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|string|max:255',
            'sex' => 'sometimes|required|string|max:255',
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
    
        $patient->update($fields);
    
        return $patient;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        $patient->delete($patient);

        return response()->noContent();
    }

    /**
     * Display the specified Patient PID.
     */
    public function getPatientById(string $pid)
    {
        $patient = Patient::where('pid', $pid)->first();

        if (! $patient) {
            return response()->json([
                'message' => 'Patient not found.'
            ], 404);
        }

        return response()->json($patient);
    }

    /**
     * Display the specified Patient Name {name_last}/{name_first}.
     */
    public function getPatientByName(string $name_last, string $name_first)
    {
        $patients = Patient::where('name_last', 'LIKE', "%{$name_last}%")
            ->where('name_first', 'LIKE', "%{$name_first}%")
            ->get();

        if ($patients->isEmpty()) {
            return response()->json([
                'message' => 'No patient found.'
            ], 404);
        }

        return response()->json($patients);
    }
}
