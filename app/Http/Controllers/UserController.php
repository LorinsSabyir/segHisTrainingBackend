<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum'),
        ];
    }

    /**
     * Display a listing of the resource.
     * Admin only.
     */
    public function index(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Only administrators can view all users.',
            ], 403);
        }

        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     * Admin only.
     */
    public function store(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Only administrators can create users.',
            ], 403);
        }

        $fields = $request->validate([
            'personnel_id' => 'nullable|string|max:255',
            'name_first' => 'required|string|max:255',
            'name_last' => 'required|string|max:255',
            'name_middle' => 'nullable|string|max:255',
            'name_suffix' => 'nullable|string|max:255',
            'role' => 'required|string|in:admin,nurse,doctor',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        try {
            $user = User::create($fields);

            return response()->json($user, 201);
        } catch (QueryException $e) {
            Log::error('Failed to create user: '.$e->getMessage());

            return response()->json([
                'message' => 'Unable to save user. Please try again.',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * Admin can view anyone; nurses/doctors can only view their own account.
     */
    public function show(Request $request, User $user)
    {
        $currentUser = $request->user();

        if ($currentUser->role !== 'admin' && $currentUser->id !== $user->id) {
            return response()->json([
                'message' => 'You are not authorized to view this user.',
            ], 403);
        }

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     * Admin can update anyone; nurses/doctors can only update their own account.
     */
    public function update(Request $request, User $user)
    {
        $currentUser = $request->user();

        if ($currentUser->role !== 'admin' && $currentUser->id !== $user->id) {
            return response()->json([
                'message' => 'You are not authorized to update this account.',
            ], 403);
        }

        $rules = [
            'personnel_id' => 'sometimes|nullable|string|max:255',
            'name_first' => 'sometimes|required|string|max:255',
            'name_last' => 'sometimes|required|string|max:255',
            'name_middle' => 'nullable|string|max:255',
            'name_suffix' => 'nullable|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|string|min:8',
        ];

        // Only admins may change a user's role.
        if ($currentUser->role === 'admin') {
            $rules['role'] = 'sometimes|required|string|in:admin,nurse,doctor';
        }

        $fields = $request->validate($rules);

        try {
            $user->update($fields);

            return response()->json($user);
        } catch (QueryException $e) {
            Log::error('Failed to update user: '.$e->getMessage());

            return response()->json([
                'message' => 'Unable to save user. Please try again.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * Admin only.
     */
    public function destroy(Request $request, User $user)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Only administrators can delete users.',
            ], 403);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.',
            'deleted' => $user,
        ]);
    }

    /**
     * Display all nurses.
     */
    public function getAllNurses(Request $request)
    {
        return User::where('role', 'nurse')->get();
    }

    /**
     * Display all doctors.
     */
    public function getAllDoctors(Request $request)
    {
        return User::where('role', 'doctor')->get();
    }
}
