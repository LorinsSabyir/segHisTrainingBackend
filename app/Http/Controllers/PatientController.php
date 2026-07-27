<?php

namespace App\Http\Controllers;

use App\Models\patient;
use App\Http\Requests\StorepatientRequest;
use App\Http\Requests\UpdatepatientRequest;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return patient::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'case_number' => 'required|string|max:255',
            'consultation_date' => 'nullable|string|max:255',
            'consultation_time' => 'nullable|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|string|max:255',
            'sex' => 'required|string|max:255',
            'blood_group' => 'nullable|string|max:255',
            'time_of_arrival' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:11',
            'address' => 'nullable|string|max:255',
        ]);

        $patient = patient::create($fields);

        $patient->phn = str_pad($patient->id, 6, '0', STR_PAD_LEFT);
        $patient->save();

        return [ 'patient' => $patient ];
    }

    /**
     * Display the specified resource.
     */
    public function show(patient $patient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, patient $patient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(patient $patient)
    {
        //
    }
}
