<?php

namespace App\Http\Controllers;

use App\Models\PatientEncounter;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
        return PatientEncounter::with(['patient', 'ward', 'nurse', 'doctor'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge([
            'consultation_time' => $this->normalizeTime($request->input('consultation_time')),
            'time_of_arrival' => $this->normalizeTime($request->input('time_of_arrival')),
        ]);

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
                'message' => 'Only nurses, doctors, and administrators can create patient encounters.',
            ], 403);
        }

        try {
            $patientEncounter = DB::transaction(function () use ($request, $fields) {

                // Generate Case Number if none is provided
                if (empty($fields['case_nr'])) {
                    $lastEncounter = PatientEncounter::latest('id')->first();
                    $next = $lastEncounter ? $lastEncounter->id + 1 : 1;
                    $fields['case_nr'] = 'CASE-'.str_pad($next, 6, '0', STR_PAD_LEFT);
                }

                $patientEncounter = PatientEncounter::create($fields);

                $request->user()->sentNotification()->create([
                    'title' => 'Patient Registered',
                    'message' => "You registered patient encounter for {$patientEncounter->name_first} {$patientEncounter->name_last} ({$patientEncounter->pid}).",
                    'category' => 'patient',
                    'priority' => 'normal',
                    'action_type' => 'patient',
                    'action_id' => $patientEncounter->id,
                    'receiver_id' => $request->user()->id,
                ]);

                return $patientEncounter;
            });



            // Generate Case Number if none is provided
            // if (empty($fields['case_nr'])) {
            //     $lastEncounter = PatientEncounter::latest('id')->first();
            //     $next = $lastEncounter ? $lastEncounter->id + 1 : 1;
            //     $fields['case_nr'] = 'CASE-'.str_pad($next, 6, '0', STR_PAD_LEFT);
            // }

            // $encounter = PatientEncounter::create($fields);

            return response()->json(
                $patientEncounter->load(['patient', 'ward', 'nurse', 'doctor']),
                201
            );
        } catch (QueryException $e) {
            Log::error('Failed to create patient encounter: '.$e->getMessage());

            return response()->json([
                'message' => 'Unable to save patient encounter. Please try again.',
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PatientEncounter $patientEncounter)
    {
        $request->merge([
            'consultation_time' => $this->normalizeTime($request->input('consultation_time')),
            'time_of_arrival' => $this->normalizeTime($request->input('time_of_arrival')),
        ]);

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

            'ward_id' => 'nullable|exists:wards,id',
        ]);

        $user = $request->user();

        if ($user->role === 'nurse') {
            $fields['nurse_id'] = $user->id;
        } elseif ($user->role === 'doctor') {
            $fields['doctor_id'] = $user->id;
        } elseif ($user->role !== 'admin') {
            return response()->json([
                'message' => 'Only nurses, doctors, and administrators can update patient encounters.',
            ], 403);
        }

        try {
            $patientEncounter->update($fields);

            return $patientEncounter->fresh()->load(['patient', 'ward', 'nurse', 'doctor']);
        } catch (QueryException $e) {
            Log::error('Failed to update patient encounter: '.$e->getMessage());

            return response()->json([
                'message' => 'Unable to save patient encounter. Please try again.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PatientEncounter $patientEncounter)
    {
        $patientEncounter->delete();

        return response()->json([
            'message' => 'Patient encounter deleted successfully.',
            'deleted' => $patientEncounter,
        ]);
    }

    /**
     * Display the specified Patient Encounters by Patient ID.
     */
    public function getPatientByPatientId(string $patient_id)
    {
        $patientEncounter = PatientEncounter::where('patient_id', $patient_id)->get();

        if ($patientEncounter->isEmpty()) {
            return response()->json([
                'message' => 'Patient Encounter not found.',
            ], 404);
        }

        return response()->json($patientEncounter);
    }

    /**
     * Normalize a time string to H:i, tolerating an optional trailing :ss,
     * and converting empty strings to null so `nullable` correctly applies.
     */
    private function normalizeTime(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        return substr($value, 0, 5);
    }

    /**
     * Searches the specified Patient Encounters.
     */
    public function search(Request $request)
{
    $query = $request->query('q');

    $patientEncounter = PatientEncounter::with(['patient', 'ward', 'nurse', 'doctor'])
        ->where(function ($q) use ($query) {
            $q->where('case_nr', 'like', "%{$query}%")
                ->orWhere('patient_type', 'like', "%{$query}%")
                ->orWhere('chief_complaint', 'like', "%{$query}%")
                ->orWhere('admitting_diagnosis', 'like', "%{$query}%")
                ->orWhereHas('patient', function ($q2) use ($query) {
                    $q2->where('pid', 'like', "%{$query}%");
                })
                ->orWhereHas('ward', function ($q2) use ($query) {
                    $q2->where('ward_id', 'like', "%{$query}%");
                })
                ->orWhereHas('nurse', function ($q2) use ($query) {
                    $q2->where('personnel_id', 'like', "%{$query}%");
                })
                ->orWhereHas('doctor', function ($q2) use ($query) {
                    $q2->where('personnel_id', 'like', "%{$query}%");
                });
        })
        ->limit(20)
        ->get();

    return response()->json($patientEncounter);
}
}