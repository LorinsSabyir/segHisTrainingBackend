<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// ---------- API routes for authentication and patient management ----------
// Auth Api
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Patient Api
Route::get('/patients/index', [PatientController::class, 'index']);
Route::post('/patients/store', [PatientController::class, 'store']);

// ---------- External API routes for SEG service ----------
use App\Http\Controllers\Api\SegDoctorController;

// Doctors API
Route::middleware('auth:sanctum')->prefix('seg')->group(function () {
  Route::get('/doctors', [SegDoctorController::class, 'index']);
  Route::get('/doctors/department/{deptId}', [SegDoctorController::class, 'byDepartment']);
  Route::get('/doctors/name/{firstName}/{lastName}', [SegDoctorController::class, 'byName']);
  Route::get('/doctors/{id}', [SegDoctorController::class, 'show']);
});

