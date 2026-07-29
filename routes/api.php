<?php
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// ---------- API routes for authentication and patient management ----------
// Auth Api
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Patient Api
use App\Http\Controllers\PatientController;

Route::get('/patient/index', [PatientController::class, 'index']);
Route::post('/patient/store', [PatientController::class, 'store']);

// ---------- External API routes for SEG service ----------
// Doctors API.
use App\Http\Controllers\Api\SegDoctorController;

Route::middleware('auth:sanctum')->prefix('seg')->group(function () {
  Route::get('/doctor', [SegDoctorController::class, 'index']);
  Route::get('/doctor/department/{deptId}', [SegDoctorController::class, 'byDepartment']);
  Route::get('/doctor/name/{firstName}/{lastName}', [SegDoctorController::class, 'byName']);
  Route::get('/doctor/{id}', [SegDoctorController::class, 'show']);
});

// TODO: Fill in the routes for the other SEG API controllers (Nurses, Departments, Encounters, Wards) as needed.
// Nurses API
use App\Http\Controllers\Api\SegNurseController;


// Department API
use App\Http\Controllers\Api\SegDepartmentController;


// Encounters API
use App\Http\Controllers\Api\SegEncountersController;


// Ward API
use App\Http\Controllers\Api\SegWardController;


// Patients API
use App\Http\Controllers\Api\SegPatientController;

Route::middleware('auth:sanctum')->prefix('seg')->group(function () {
  Route::get('/patient', [SegPatientController::class, 'index']);
  Route::get('/patient/department/{id}', [SegPatientController::class, 'byId']);
  Route::get('/patient/name/{lastName}/{firstName}', [SegPatientController::class, 'byName']);
  Route::get('/patient/{id}', [SegPatientController::class, 'show']);
});