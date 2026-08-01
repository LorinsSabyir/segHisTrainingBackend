<?php

namespace App\Http\Controllers;

use App\Models\Radiology;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;

class RadiologyController extends Controller implements HasMiddleware
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
        return Radiology::orderBy('test')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'code' => 'required|string|max:255|unique:radiologies,code',
            'test' => 'required|string|max:255',
            'group_code' => 'required|string|max:255',
            'group' => 'required|string|max:255',
            'section_code' => 'required|string|max:255',
            'section' => 'required|string|max:255',
        ]);

        try {

            $radiology = Radiology::create($fields);

            return response()->json($radiology, 201);

        } catch (QueryException $e) {

            Log::error('Failed to create radiology: ' . $e->getMessage());

            return response()->json([
                'message' => 'Unable to save radiology.',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Radiology $radiology)
    {
        return response()->json($radiology);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Radiology $radiology)
    {
        $fields = $request->validate([
            'code' => 'sometimes|required|string|max:255|unique:radiologies,code,' . $radiology->id,
            'test' => 'sometimes|required|string|max:255',
            'group_code' => 'sometimes|required|string|max:255',
            'group' => 'sometimes|required|string|max:255',
            'section_code' => 'sometimes|required|string|max:255',
            'section' => 'sometimes|required|string|max:255',
        ]);

        try {

            $radiology->update($fields);

            return response()->json($radiology);

        } catch (QueryException $e) {

            Log::error('Failed to update radiology: ' . $e->getMessage());

            return response()->json([
                'message' => 'Unable to update radiology.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Radiology $radiology)
    {
        $radiology->delete();

        return response()->json([
            'message' => 'Radiology deleted successfully.',
            'deleted' => $radiology,
        ]);
    }

    /**
     * Search radiology records.
     */
    public function search(Request $request)
    {
        $query = trim($request->input('q', ''));

        if ($query === '') {
            return Radiology::orderBy('test')->get();
        }

        $radiologies = Radiology::where('code', 'like', "%{$query}%")
            ->orWhere('test', 'like', "%{$query}%")
            ->orWhere('group_code', 'like', "%{$query}%")
            ->orWhere('group', 'like', "%{$query}%")
            ->orWhere('section_code', 'like', "%{$query}%")
            ->orWhere('section', 'like', "%{$query}%")
            ->orderBy('test')
            ->get();

        return response()->json($radiologies);
    }
}