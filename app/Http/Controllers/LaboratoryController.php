<?php

namespace App\Http\Controllers;

use App\Models\Laboratory;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;

class LaboratoryController extends Controller implements HasMiddleware
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
        return Laboratory::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'code' => 'nullable|string|max:255|unique:laboratories,code',
            'test' => 'nullable|string|max:255',
            'section_code' => 'nullable|string|max:255',
            'section' => 'nullable|string|max:255',
            'opd' => 'nullable|numeric',
            'ipd' => 'nullable|numeric',
        ]);

        try {

            $laboratory = Laboratory::create($fields);

            return response()->json($laboratory, 201);

        } catch (QueryException $e) {

            Log::error('Failed to create laboratory: '.$e->getMessage());

            return response()->json([
                'message' => 'Unable to save laboratory.',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Laboratory $laboratory)
    {
        return response()->json($laboratory);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Laboratory $laboratory)
    {
        $fields = $request->validate([
            'code' => 'nullable|string|max:255|unique:laboratories,code,' . $laboratory->id,
            'test' => 'nullable|string|max:255',
            'section_code' => 'nullable|string|max:255',
            'section' => 'nullable|string|max:255',
            'opd' => 'nullable|numeric',
            'ipd' => 'nullable|numeric',
        ]);

        try {

            $laboratory->update($fields);

            return response()->json($laboratory);

        } catch (QueryException $e) {

            Log::error('Failed to update laboratory: '.$e->getMessage());

            return response()->json([
                'message' => 'Unable to update laboratory.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Laboratory $laboratory)
    {
        $laboratory->delete();

        return response()->json([
            'message' => 'Laboratory deleted successfully.',
            'deleted' => $laboratory,
        ]);
    }

    /**
     * Search laboratory records.
     */
    public function search(Request $request)
    {
        $query = trim($request->input('q', ''));

        if ($query === '') {
            return Laboratory::orderBy('test')->get();
        }

        $laboratories = Laboratory::where('code', 'like', "%{$query}%")
            ->orWhere('test', 'like', "%{$query}%")
            ->orWhere('section_code', 'like', "%{$query}%")
            ->orWhere('section', 'like', "%{$query}%")
            ->orderBy('test')
            ->get();

        return response()->json($laboratories);
    }
    
}