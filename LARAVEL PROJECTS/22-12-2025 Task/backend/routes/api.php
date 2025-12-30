<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskApiController;
use App\Http\Controllers\Api\UserApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        
        // Tasks API
        Route::apiResource('tasks', TaskApiController::class);
        Route::get('/my-tasks', [TaskApiController::class, 'myTasks']);
        Route::put('/tasks/{task}/status', [TaskApiController::class, 'updateStatus']);
        
        // Users API
        Route::get('/users', [UserApiController::class, 'index']);
        Route::get('/me', [UserApiController::class, 'me']);
        Route::get('/assignable-users', [UserApiController::class, 'assignableUsers']);
    });
});