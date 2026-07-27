<?php

namespace App\Http\Controllers;

use App\Models\nurse;
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
    public function show(nurse $nurse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatenurseRequest $request, nurse $nurse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(nurse $nurse)
    {
        //
    }
}
