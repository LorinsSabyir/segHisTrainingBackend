<?php

namespace App\Http\Controllers;

use App\Models\Ward;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;

class WardController extends Controller implements HasMiddleware
{
    public static function middleware(): array
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
        return Ward::orderBy('description')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'ward_id' => 'required|string|max:255|unique:wards,ward_id',
            'description' => 'required|string|max:255',
            'dept_nr' => 'required|string|max:255',
        ]);

        try {

            $ward = Ward::create($fields);

            return response()->json($ward, 201);

        } catch (QueryException $e) {

            Log::error('Failed to create ward: ' . $e->getMessage());

            return response()->json([
                'message' => 'Unable to save ward.',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Ward $ward)
    {
        return response()->json($ward);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ward $ward)
    {
        $fields = $request->validate([
            'ward_id' => 'sometimes|required|string|max:255|unique:wards,ward_id,' . $ward->id,
            'description' => 'sometimes|required|string|max:255',
            'dept_nr' => 'sometimes|required|string|max:255',
        ]);

        try {

            $ward->update($fields);

            return response()->json($ward);

        } catch (QueryException $e) {

            Log::error('Failed to update ward: ' . $e->getMessage());

            return response()->json([
                'message' => 'Unable to update ward.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ward $ward)
    {
        $ward->delete();

        return response()->json([
            'message' => 'Ward deleted successfully.',
            'deleted' => $ward,
        ]);
    }

    /**
     * Search wards.
     */
    public function search(Request $request)
    {
        $query = trim($request->input('q', ''));

        if ($query === '') {
            return Ward::orderBy('description')->get();
        }

        $wards = Ward::where('ward_id', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhere('dept_nr', 'like', "%{$query}%")
            ->orderBy('description')
            ->get();

        return response()->json($wards);
    }
}