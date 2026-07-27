<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        //
    }

    public function login(LoginRequest $request)
    {
        return response()->json([
            'message' => 'Validation passed.'
        ]);
    }

    public function logout(Request $request)
    {
        //
    }

    public function me(Request $request)
    {
        //
    }
}
