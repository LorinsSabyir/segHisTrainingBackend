<?php

namespace App\Http\Controllers;

use App\Models\Nurse;
use App\Http\Requests\StorenurseRequest;
use App\Http\Requests\UpdatenurseRequest;
use App\Illuminate\Http\Request;

class NurseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorenurseRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Nurse $nurse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatenurseRequest $request, Nurse $nurse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Nurse $nurse)
    {
        //
    }
}
