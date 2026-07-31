<?php
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// ---------- API routes for authentication ----------
// Auth Api
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


// ---------- API routes for account management ----------
use App\Http\Controllers\UserController;

Route::middleware('auth:sanctum')->prefix('user')->group(function () {

});



// ---------- API routes for admin user management ----------
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {

});




// ---------- API routes for hospital management ----------
// Patient Api
use App\Http\Controllers\PatientController;

Route::middleware('auth:sanctum')->prefix('patient')->group(function () {
  Route::get('/', [PatientController::class, 'index']);
  Route::post('/store', [PatientController::class, 'store']);
  Route::get('/show/{patient}', [PatientController::class, 'show']);
  Route::put('/update/{patient}', [PatientController::class, 'update']);
  Route::delete('/delete/{patient}', [PatientController::class, 'destroy']);

  Route::get('/pid/{pid}', [PatientController::class, 'getPatientByPid']);
  Route::get('/name/{name_last}/{name_first}', [PatientController::class, 'getPatientByName']);
  
});

// Encounter Api
use App\Http\Controllers\EncounterController;

Route::middleware('auth:sanctum')->prefix('patient_encounter')->group(function () {

});

// Ward Api
use App\Http\Controllers\WardController;

Route::middleware('auth:sanctum')->prefix('ward')->group(function () {

});

// Department Api
use App\Http\Controllers\DepartmentController;

Route::middleware('auth:sanctum')->prefix('department')->group(function () {

});



// ---------- External API routes for SEG service ----------
// Doctors API.
use App\Http\Controllers\Api\SegDoctorController;

Route::middleware('auth:sanctum')->prefix('seg/doctor')->group(function () {
  Route::get('/', [SegDoctorController::class, 'index']);
  Route::get('/department/{deptId}', [SegDoctorController::class, 'byDepartment']);
  Route::get('/name/{firstName}/{lastName}', [SegDoctorController::class, 'byName']);
  Route::get('/{id}', [SegDoctorController::class, 'show']);
});

// TODO: Fill in the routes for the other SEG API controllers (Nurses, Departments, Encounters, Wards) as needed.
// Nurses API
use App\Http\Controllers\Api\SegNurseController;

Route::middleware('auth:sanctum')->prefix('seg/nurse')->group(function () {

});

// Department API
use App\Http\Controllers\Api\SegDepartmentController;

Route::middleware('auth:sanctum')->prefix('seg/department')->group(function () {

});

// Encounters API
use App\Http\Controllers\Api\SegEncountersController;

Route::middleware('auth:sanctum')->prefix('seg/patient_encounters')->group(function () {

});

// Ward API
use App\Http\Controllers\Api\SegWardController;

Route::middleware('auth:sanctum')->prefix('seg/ward')->group(function () {

});

// Patients API
use App\Http\Controllers\Api\SegPatientController;

Route::middleware('auth:sanctum')->prefix('seg/patient')->group(function () {
  Route::get('/', [SegPatientController::class, 'index']);
  Route::get('/{id}', [SegPatientController::class, 'byId']);
  Route::get('/name/{lastName}/{firstName}', [SegPatientController::class, 'byName']);
  Route::get('/{id}', [SegPatientController::class, 'show']);
});