<?php

use App\Http\Controllers\AuthController;
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// ---------- API routes for authentication ----------

// Auth Api
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// ---------- API routes for user management ----------
use App\Http\Controllers\UserController;
// Account API
Route::middleware(['auth:sanctum'])->prefix('user')->group(function () {
  Route::get('/show/{user}', [UserController::class, 'show']);
  Route::put('/update/{user}', [UserController::class, 'update']);
  Route::get('/nurse', [UserController::class, 'getAllNurses']);
  Route::get('/doctors', [UserController::class, 'getAllDoctors']);
});

// Admin only API
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
  Route::get('/', [UserController::class, 'index']);
  Route::post('/store', [UserController::class, 'store']);
  Route::delete('/delete/{user}', [UserController::class, 'destroy']);

});


// ---------- API routes for hospital management ----------
// Patient Api
use App\Http\Controllers\PatientController;

Route::middleware(['auth:sanctum', 'role:admin,nurse,doctor'])->prefix('patient')->group(function () {

    Route::get('/', [PatientController::class, 'index']);
    Route::post('/store', [PatientController::class, 'store']);
    Route::get('/show/{patient}', [PatientController::class, 'show']);
    Route::put('/update/{patient}', [PatientController::class, 'update']);
    Route::delete('/delete/{patient}', [PatientController::class, 'destroy']);

    Route::get('/pid/{pid}', [PatientController::class, 'getPatientByPid']);
    Route::get('/name/{name_last}/{name_first}', [PatientController::class, 'getPatientByName']);

    Route::get('/search', [PatientController::class, 'search']);

});

// Notification Api
use App\Http\Controllers\NotificationController;

Route::middleware(['auth:sanctum', 'role:admin,nurse,doctor'])->prefix('notification')->group(function () {

    Route::get('/', [NotificationController::class, 'index']);
    Route::post('/store', [NotificationController::class, 'store']);
    Route::get('/show/{notification}', [NotificationController::class, 'show']);
    Route::put('/update/{notification}', [NotificationController::class, 'update']);
    Route::delete('/delete/{notification}', [NotificationController::class, 'destroy']);

    Route::patch('/{notification}', [NotificationController::class, 'markAsRead']);

});

// Encounter Api
use App\Http\Controllers\PatientEncounterController;

Route::middleware(['auth:sanctum', 'role:admin,nurse,doctor'])->prefix('patient_encounter')->group(function () {

    Route::get('/', [PatientEncounterController::class, 'index']);
    Route::post('/store', [PatientEncounterController::class, 'store']);
    Route::get('/show/{patientEncounter}', [PatientEncounterController::class, 'show']);
    Route::put('/update/{patientEncounter}', [PatientEncounterController::class, 'update']);
    Route::delete('/delete/{patientEncounter}', [PatientEncounterController::class, 'destroy']);

    Route::get('/pid/{patient_id}', [PatientEncounterController::class, 'getPatientByPatientId']);

    Route::get('/search', [PatientEncounterController::class, 'search']);

});

// Ward Api
use App\Http\Controllers\WardController;

Route::middleware('auth:sanctum')->prefix('ward')->group(function () {  

    // Everyone can view
    Route::middleware('role:admin,nurse,doctor')->group(function () {
        Route::get('/', [WardController::class, 'index']);
        Route::get('/show/{ward}', [WardController::class, 'show']);
        Route::get('/search', [WardController::class, 'search']);
    });

    // Only admin can modify
    Route::middleware('role:admin')->group(function () {
        Route::post('/store', [WardController::class, 'store']);
        Route::put('/update/{ward}', [WardController::class, 'update']);
        Route::delete('/delete/{ward}', [WardController::class, 'destroy']);
    });

});

// Department Api
use App\Http\Controllers\DepartmentController;

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('department')->group(function () {

  Route::middleware('role:admin,nurse,doctor')->group(function () {
    Route::get('/', [DepartmentController::class, 'index']);
    Route::get('/show/{ward}', [DepartmentController::class, 'show']);
    Route::get('/search', [DepartmentController::class, 'search']);
  });

  // Only admin can modify
  Route::middleware('role:admin')->group(function () {
    Route::post('/store', [DepartmentController::class, 'store']);
    Route::put('/update/{ward}', [DepartmentController::class, 'update']);
    Route::delete('/delete/{ward}', [DepartmentController::class, 'destroy']);
  });

});

// Radiology Api
use App\Http\Controllers\RadiologyController;

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('radiology')->group(function () {

  Route::middleware('role:admin,nurse,doctor')->group(function () {
    Route::get('/', [RadiologyController::class, 'index']);
    Route::get('/show/{laboratory}', [RadiologyController::class, 'show']);
    Route::get('/search', [RadiologyController::class, 'search']);
  });

  Route::middleware('role:admin')->group(function () {
    Route::post('/store', [RadiologyController::class, 'store']);
    Route::put('/update/{laboratory}', [RadiologyController::class, 'update']);
    Route::delete('/delete/{laboratory}', [RadiologyController::class, 'destroy']);
  });

});

// Laboratory Api
use App\Http\Controllers\LaboratoryController;

Route::middleware('auth:sanctum')->prefix('laboratory')->group(function () {

  Route::middleware('role:admin,nurse,doctor')->group(function () {
      Route::get('/', [LaboratoryController::class, 'index']);
      Route::get('/show/{laboratory}', [LaboratoryController::class, 'show']);
      Route::get('/search', [LaboratoryController::class, 'search']);
  });

  Route::middleware('role:admin')->group(function () {
      Route::post('/store', [LaboratoryController::class, 'store']);
      Route::put('/update/{laboratory}', [LaboratoryController::class, 'update']);
      Route::delete('/delete/{laboratory}', [LaboratoryController::class, 'destroy']);
  });

});

// ---------- External API routes for SEG service ----------

// Doctors API.
use App\Http\Controllers\Api\SegDoctorController;

Route::middleware(['auth:sanctum', 'role:admin,nurse,doctor'])->prefix('seg/doctor')->group(function () {

    Route::get('/', [SegDoctorController::class, 'index']);
    Route::get('/department/{deptId}', [SegDoctorController::class, 'byDepartment']);
    Route::get('/name/{firstName}/{lastName}', [SegDoctorController::class, 'byName']);
    Route::get('/{id}', [SegDoctorController::class, 'show']);

});

// Nurses API
use App\Http\Controllers\Api\SegNurseController;

Route::middleware(['auth:sanctum', 'role:admin,nurse,doctor'])->prefix('seg/nurse')->group(function () {

    Route::get('/', [SegNurseController::class, 'index']);
    Route::get('/{id}', [SegNurseController::class, 'show']);

});

// Department API
use App\Http\Controllers\Api\SegDepartmentController;

Route::middleware(['auth:sanctum', 'role:admin,nurse,doctor'])->prefix('seg/department')->group(function () {

    Route::get('/', [SegDepartmentController::class, 'index']);
    Route::get('/{id}', [SegDepartmentController::class, 'show']);

});

// Encounters API
use App\Http\Controllers\Api\SegEncounterController;

Route::middleware(['auth:sanctum', 'role:admin,nurse,doctor'])->prefix('seg/patient_encounters')->group(function () {

    Route::get('/', [SegEncounterController::class, 'index']);
    Route::get('/{id}', [SegEncounterController::class, 'show']);

});

// Laboratory API
use App\Http\Controllers\Api\SegLaboratoryController;

Route::middleware(['auth:sanctum', 'role:admin,nurse,doctor'])->prefix('seg/laboratory')->group(function () {

    Route::get('/', [SegLaboratoryController::class, 'index']);
    Route::get('/{id}', [SegLaboratoryController::class, 'show']);

});

// Ward API
use App\Http\Controllers\Api\SegWardController;

Route::middleware(['auth:sanctum', 'role:admin,nurse,doctor'])->prefix('seg/ward')->group(function () {

    Route::get('/', [SegWardController::class, 'index']);
    Route::get('/{id}', [SegWardController::class, 'show']);

});

// Radiology API
use App\Http\Controllers\Api\SegRadiologyController;

Route::middleware(['auth:sanctum', 'role:admin,nurse,doctor'])->prefix('seg/radiology')->group(function () {

    Route::get('/', [SegRadiologyController::class, 'index']);
    Route::get('/{id}', [SegRadiologyController::class, 'show']);

});

// Patients API
use App\Http\Controllers\Api\SegPatientController;

Route::middleware(['auth:sanctum', 'role:admin,nurse,doctor'])->prefix('seg/patient')->group(function () {

    Route::get('/', [SegPatientController::class, 'index']);
    Route::get('/{id}', [SegPatientController::class, 'byId']);
    Route::get('/name/{lastName}/{firstName}', [SegPatientController::class, 'byName']);
    Route::get('/{id}', [SegPatientController::class, 'show']);

});
