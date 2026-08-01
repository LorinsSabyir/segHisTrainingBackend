<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller implements HasMiddleware
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
        return Department::orderBy('dept_name')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'deptnr' => 'nullable|string|max:255',
            'deptid' => 'nullable|string|max:255',
            'dept_name' => 'required|string|max:255',
            'dept_shortname' => 'nullable|string|max:255',
            'parent_dept_nr' => 'nullable|string|max:255',
            'parent_name' => 'nullable|string|max:255',
        ]);

        try {

            $department = Department::create($fields);

            return response()->json($department, 201);

        } catch (QueryException $e) {

            Log::error('Failed to create department: ' . $e->getMessage());

            return response()->json([
                'message' => 'Unable to save department.',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        return response()->json($department);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $fields = $request->validate([
            'deptnr' => 'nullable|string|max:255',
            'deptid' => 'nullable|string|max:255',
            'dept_name' => 'sometimes|required|string|max:255',
            'dept_shortname' => 'nullable|string|max:255',
            'parent_dept_nr' => 'nullable|string|max:255',
            'parent_name' => 'nullable|string|max:255',
        ]);

        try {

            $department->update($fields);

            return response()->json($department);

        } catch (QueryException $e) {

            Log::error('Failed to update department: ' . $e->getMessage());

            return response()->json([
                'message' => 'Unable to update department.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $department->delete();

        return response()->json([
            'message' => 'Department deleted successfully.',
            'deleted' => $department,
        ]);
    }

    /**
     * Search departments.
     */
    public function search(Request $request)
    {
        $query = trim($request->input('q', ''));

        if ($query === '') {
            return Department::orderBy('dept_name')->get();
        }

        $departments = Department::where('deptnr', 'like', "%{$query}%")
            ->orWhere('deptid', 'like', "%{$query}%")
            ->orWhere('dept_name', 'like', "%{$query}%")
            ->orWhere('dept_shortname', 'like', "%{$query}%")
            ->orWhere('parent_dept_nr', 'like', "%{$query}%")
            ->orWhere('parent_name', 'like', "%{$query}%")
            ->orderBy('dept_name')
            ->get();

        return response()->json($departments);
    }
}