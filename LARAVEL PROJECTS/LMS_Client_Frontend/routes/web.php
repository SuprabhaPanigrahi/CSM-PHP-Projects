<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveController;

// Auth routes
Route::post('/api/login', [AuthController::class, 'login']);
Route::post('/api/logout', [AuthController::class, 'logout']);

// Employee routes with role-based middleware
Route::middleware(['auth.basic'])->group(function () {
    // Public routes (EMPLOYEE and MANAGER can access)
    Route::get('/api/employees', [EmployeeController::class, 'index']);
    Route::get('/api/employees/{id}', [EmployeeController::class, 'show']);
    
    // MANAGER only routes
    Route::middleware(['role:MANAGER'])->group(function () {
        // Employee CRUD
        Route::post('/api/employees', [EmployeeController::class, 'store']);
        Route::put('/api/employees/{id}', [EmployeeController::class, 'update']);
        Route::delete('/api/employees/{id}', [EmployeeController::class, 'destroy']);
        Route::delete('/api/employees', [EmployeeController::class, 'destroyAll']);
        
        // Leave CRUD
        Route::post('/api/leaves/employees/{employeeId}', [LeaveController::class, 'store']);
        Route::put('/api/leaves/{id}', [LeaveController::class, 'update']);
        Route::delete('/api/leaves/{id}', [LeaveController::class, 'destroy']);
        Route::delete('/api/leaves', [LeaveController::class, 'destroyAll']);
    });
    
    // Leave routes accessible by both roles
    Route::get('/api/leaves', [LeaveController::class, 'index']);
    Route::get('/api/leaves/{id}', [LeaveController::class, 'show']);
    
    // Employee can create and update their own leaves
    Route::middleware(['role:EMPLOYEE,MANAGER'])->group(function () {
        Route::post('/api/leaves/employees/{employeeId}', [LeaveController::class, 'store']);
        Route::put('/api/leaves/{id}', [LeaveController::class, 'update']);
    });
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/', function () {
    return redirect('/dashboard');
});




//database add a column role(manager, employee)
//create a login page with auth Controllers
//employee name password
//in logincontroller , check from database username is there or not 
//extract everything like role store in session
//if success redirect to dashboard
//middleware - authenticated or not 
//              check for role