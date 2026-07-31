<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $fields = $request->validate([
            'name_first' => 'required|string|max:255',
            'name_last' => 'required|string|max:255',
            'name_middle' => 'nullable|string|max:255',
            'name_suffix' => 'nullable|string|max:255',
            'role' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $fields['password'] = Hash::make($fields['password']);

        $user = User::create($fields);

        $token = $user->createToken($request->name_first);

        return [
            'name_first' => $user->name_first,
            'name_last' => $user->name_last,
            'name_middle' => $user->name_middle,
            'name_suffix' => $user->name_suffix,
            'role' => $user->role,
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if  ( !$user ||  !Hash::check($request->password, $user->password)) {
            return [
                'message' => 'Invalid Credentials.'
            ];
        }

        $token = $user->createToken($user->name_first);

        return [
            'name_first' => $user->name_first,
            'name_last' => $user->name_last,
            'name_middle' => $user->name_middle,
            'name_suffix' => $user->name_suffix,
            'role' => $user->role,
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return [
            'message' => 'You Logged Out.'
        ];
    }

    // TODO: add delete user for each user
    // TODO: add update user for each user

    // TODO: add delete user for admin role 
    // TODO: add update user for admin role

    

}
