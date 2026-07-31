<?php

namespace App\Http\Controllers;

use App\Models\PatientEncounter;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PatientEncounterController extends Controller implements HasMiddleware
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
        return PatientEncounter::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $fields = $request->validate([
                'case_nr' => 'nullable|string|max:255',
                'encounter_date' => 'nullable|date',
                'patient_type' => 'required|string|max:255',
                'official_receipt_nr' => 'nullable|string|max:255',
                'admitting_diagnosis' => 'nullable|string',
                'chief_complaint' => 'nullable|string',
                'is_confidential' => 'nullable|boolean',
                'discharge_datetime' => 'nullable|date',
                'iswaitlisted' => 'nullable|boolean',
                'is_still_in' => 'nullable|boolean',
                'consultation_date' => 'nullable|date',
                'consultation_time' => 'nullable|date_format:H:i',
                'time_of_arrival' => 'nullable|date_format:H:i',

                'patient_id' => 'required|exists:patients,id',
                'ward_id' => 'nullable|exists:wards,id',
            ]);

            $user = $request->user();

            if ($user->role === 'nurse') {
                $fields['nurse_id'] = $user->id;
            } elseif ($user->role === 'doctor') {
                $fields['doctor_id'] = $user->id;
            } elseif ($user->role !== 'admin') {
                return response()->json([
                    'message' => 'Only nurses, doctors, and administrators can create patient encounters.'
                ], 403);
            }

            // Generate Case Number if none is provided
            if (empty($fields['case_nr'])) {
                $lastEncounter = PatientEncounter::latest('id')->first();

                $next = $lastEncounter ? $lastEncounter->id + 1 : 1;

                $fields['case_nr'] = 'CASE-' . str_pad($next, 6, '0', STR_PAD_LEFT);
            }

            $encounter = PatientEncounter::create($fields);

            return response()->json([
                'message' => 'Patient encounter created successfully.',
                'data' => $encounter->load([
                    'patient',
                    'ward',
                    'nurse',
                    'doctor',
                ]),
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Failed to create patient encounter.',
                'error' => $e->getMessage(),
            ], 500);

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PatientEncounter $patientEncounter)
    {
        return response($patientEncounter);
    }

    // Displaying the personnel_id instead of id
    // $encounter->nurse->personnel_id;

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PatientEncounter $patientEncounter)
    {
        try {

            $fields = $request->validate([
                'case_nr' => 'sometimes|string|max:255',
                'encounter_date' => 'sometimes|date',
                'patient_type' => 'sometimes|required|string|max:255',
                'official_receipt_nr' => 'nullable|string|max:255',
                'admitting_diagnosis' => 'nullable|string',
                'chief_complaint' => 'nullable|string',
                'is_confidential' => 'nullable|boolean',
                'discharge_datetime' => 'nullable|date',
                'iswaitlisted' => 'nullable|boolean',
                'is_still_in' => 'nullable|boolean',
                'consultation_date' => 'nullable|date',
                'consultation_time' => 'nullable|date_format:H:i',
                'time_of_arrival' => 'nullable|date_format:H:i',

                'ward_id' => 'nullable|exists:wards,id',
            ]);

            $user = $request->user();

            if ($user->role === 'nurse') {
                $fields['nurse_id'] = $user->id;
            } elseif ($user->role === 'doctor') {
                $fields['doctor_id'] = $user->id;
            } elseif ($user->role !== 'admin') {
                return response()->json([
                    'message' => 'Only nurses, doctors, and administrators can update patient encounters.'
                ], 403);
            }

            $patientEncounter->update($fields);

            return response()->json([
                'message' => 'Patient encounter updated successfully.',
                'data' => $patientEncounter->fresh()->load([
                    'patient',
                    'ward',
                    'nurse',
                    'doctor',
                ]),
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Failed to update patient encounter.',
                'error' => $e->getMessage(),
            ], 500);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PatientEncounter $patientEncounter)
    {
        $patientEncounter->delete($patientEncounter);

        return response()->json([
            'message' => 'Patient deleted successfully.',
            'deleted' => $patientEncounter,
        ]);
    }

    /**
     * Display the specified Patient PID.
     */
    public function getPatientByPatientId(string $patient_id)
    {
        $patientEncounter = PatientEncounter::where('patient_id', $patient_id)->get();

        if (! $patientEncounter) {
            return response()->json([
                'message' => 'Patient Encounter not found.'
            ], 404);
        }

        return response()->json($patientEncounter);
    }

}
