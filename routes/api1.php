<?php

use Illuminate\Support\Facades\Route;

// ==========================================
// Authentication
// ==========================================

use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');


// ==========================================
// Controllers
// ==========================================

use App\Http\Controllers\UserController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientEncounterController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WardController;
use App\Http\Controllers\DepartmentController;

use App\Http\Controllers\Api\SegDoctorController;
use App\Http\Controllers\Api\SegNurseController;
use App\Http\Controllers\Api\SegDepartmentController;
use App\Http\Controllers\Api\SegEncounterController;
use App\Http\Controllers\Api\SegLaboratoryController;
use App\Http\Controllers\Api\SegWardController;
use App\Http\Controllers\Api\SegPatientController;


// ==========================================
// Authenticated Routes
// ==========================================

Route::middleware('auth:sanctum')->group(function () {

    // ======================================
    // Current User
    // ======================================

    Route::prefix('user')->group(function () {
        // future routes
    });

    // ======================================
    // Notifications
    // Admin / Doctor / Nurse
    // ======================================

    Route::middleware('role:admin,doctor,nurse')
        ->prefix('notification')
        ->group(function () {

            Route::get('/', [NotificationController::class, 'index']);
            Route::post('/store', [NotificationController::class, 'store']);
            Route::get('/show/{notification}', [NotificationController::class, 'show']);
            Route::put('/update/{notification}', [NotificationController::class, 'update']);
            Route::delete('/delete/{notification}', [NotificationController::class, 'destroy']);

            Route::patch('/{notification}', [NotificationController::class, 'markAsRead']);
        });

    // ======================================
    // Patients
    // Admin + Nurse
    // ======================================

    Route::middleware('role:admin,nurse')
        ->prefix('patient')
        ->group(function () {

            Route::get('/', [PatientController::class, 'index']);
            Route::post('/store', [PatientController::class, 'store']);
            Route::get('/show/{patient}', [PatientController::class, 'show']);
            Route::put('/update/{patient}', [PatientController::class, 'update']);
            Route::delete('/delete/{patient}', [PatientController::class, 'destroy']);

            Route::get('/pid/{pid}', [PatientController::class, 'getPatientByPid']);
            Route::get('/name/{name_last}/{name_first}', [PatientController::class, 'getPatientByName']);
            Route::get('/search', [PatientController::class, 'search']);
        });

    // ======================================
    // Patient Encounters
    // Admin + Doctor + Nurse
    // ======================================

    Route::middleware('role:admin,doctor,nurse')
        ->prefix('patient_encounter')
        ->group(function () {

            Route::get('/', [PatientEncounterController::class, 'index']);
            Route::post('/store', [PatientEncounterController::class, 'store']);
            Route::get('/show/{patientEncounter}', [PatientEncounterController::class, 'show']);
            Route::put('/update/{patientEncounter}', [PatientEncounterController::class, 'update']);
            Route::delete('/delete/{patientEncounter}', [PatientEncounterController::class, 'destroy']);

            Route::get('/pid/{patient_id}', [PatientEncounterController::class, 'getPatientByPatientId']);
            Route::get('/search', [PatientEncounterController::class, 'search']);
        });

    // ======================================
    // Admin Only
    // ======================================

    Route::middleware('role:admin')
        ->prefix('admin')
        ->group(function () {

            // User Management
            // Reports
            // Billing
            // Audit Logs
        });

    // ======================================
    // Wards
    // ======================================

    Route::middleware('role:admin')
        ->prefix('ward')
        ->group(function () {

        });

    // ======================================
    // Departments
    // ======================================

    Route::middleware('role:admin')
        ->prefix('department')
        ->group(function () {

        });

    // ======================================
    // SEG APIs
    // ======================================

    Route::prefix('seg')->group(function () {

        Route::prefix('doctor')->group(function () {
            Route::get('/', [SegDoctorController::class, 'index']);
            Route::get('/department/{deptId}', [SegDoctorController::class, 'byDepartment']);
            Route::get('/name/{firstName}/{lastName}', [SegDoctorController::class, 'byName']);
            Route::get('/{id}', [SegDoctorController::class, 'show']);
        });

        Route::prefix('nurse')->group(function () {
            Route::get('/', [SegNurseController::class, 'index']);
            Route::get('/{id}', [SegNurseController::class, 'show']);
        });

        Route::prefix('department')->group(function () {
            Route::get('/', [SegDepartmentController::class, 'index']);
            Route::get('/{id}', [SegDepartmentController::class, 'show']);
        });

        Route::prefix('patient_encounters')->group(function () {
            Route::get('/', [SegEncounterController::class, 'index']);
            Route::get('/{id}', [SegEncounterController::class, 'show']);
        });

        Route::prefix('laboratory')->group(function () {
            Route::get('/', [SegLaboratoryController::class, 'index']);
            Route::get('/{id}', [SegLaboratoryController::class, 'show']);
        });

        Route::prefix('ward')->group(function () {
            Route::get('/', [SegWardController::class, 'index']);
            Route::get('/{id}', [SegWardController::class, 'show']);
        });

        Route::prefix('patient')->group(function () {
            Route::get('/', [SegPatientController::class, 'index']);
            Route::get('/id/{id}', [SegPatientController::class, 'byId']);
            Route::get('/name/{lastName}/{firstName}', [SegPatientController::class, 'byName']);
            Route::get('/{id}', [SegPatientController::class, 'show']);
        });
    });

});