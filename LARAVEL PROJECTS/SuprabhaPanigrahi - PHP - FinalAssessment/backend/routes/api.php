<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AssignmentController;

// Public routes (no authentication required)
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected routes (require JWT authentication)
Route::middleware(['jwt.auth'])->group(function () {
    // Auth routes - accessible to all authenticated users
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Get employee ID 
    Route::get('/auth/my-employee-id', [AuthController::class, 'getMyEmployeeId'])
        ->middleware('jwt.auth:employee');

    // ======== EMPLOYEE ROUTES ========
    // View employees - accessible to admin, manager
    Route::get('/employees', [EmployeeController::class, 'index'])
        ->middleware('jwt.auth:admin,manager');
    Route::get('/employees/{id}', [EmployeeController::class, 'show'])
        ->middleware('jwt.auth:admin,manager');

    // CRUD operations - ONLY for manager
    Route::middleware(['jwt.auth:manager'])->group(function () {
        Route::post('/employees', [EmployeeController::class, 'store']);
        Route::put('/employees/{id}', [EmployeeController::class, 'update']);
        Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);
    });

    // ======== PROJECT ROUTES ========
    // View projects - accessible to admin, manager
    Route::get('/projects', [ProjectController::class, 'index'])
        ->middleware('jwt.auth:admin,manager');
    Route::get('/projects/{id}', [ProjectController::class, 'show'])
        ->middleware('jwt.auth:admin,manager');

    // CRUD operations - ONLY for manager
    Route::middleware(['jwt.auth:manager'])->group(function () {
        Route::post('/projects', [ProjectController::class, 'store']);
        Route::put('/projects/{id}', [ProjectController::class, 'update']);
        Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
    });

    // ======== ASSIGNMENT ROUTES ========
    // View assignments - accessible to admin, manager, employee
    Route::get('/assignments', [AssignmentController::class, 'viewAssignedProjects']);
    Route::get('/assignments/employee/{employeeId}', [AssignmentController::class, 'viewAssignedProjects']);

    // Assign project - accessible to admin AND manager
    Route::middleware(['jwt.auth:admin,manager'])->group(function () {
        Route::post('/assignments/assign', [AssignmentController::class, 'assignEmployeeToProject']);
        Route::post('/assignments/remove', [AssignmentController::class, 'removeEmployeeFromProject']);
    });

    Route::get('/my-projects/{employeeId}', [ProjectController::class, 'getEmployeeProjects'])
        ->middleware(['jwt.auth']);
});
