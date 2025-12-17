<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Test route (keep this to verify)
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

// EMPLOYEE ROUTES - matching your PDF exactly
Route::prefix('employees')->group(function () {
    Route::get('/', [EmployeeController::class, 'index']);           // GET /api/employees
    Route::post('/', [EmployeeController::class, 'store']);          // POST /api/employees
    Route::delete('/', [EmployeeController::class, 'destroyAll']);   // DELETE /api/employees
    
    Route::get('/{id}', [EmployeeController::class, 'show']);        // GET /api/employees/{id}
    Route::put('/{id}', [EmployeeController::class, 'update']);      // PUT /api/employees/{id}
    Route::delete('/{id}', [EmployeeController::class, 'destroy']);  // DELETE /api/employees/{id}
});

// LEAVE ROUTES - matching your PDF exactly
Route::prefix('leaves')->group(function () {
    Route::get('/', [LeaveController::class, 'index']);              // GET /api/leaves
    Route::delete('/', [LeaveController::class, 'destroyAll']);      // DELETE /api/leaves
    
    Route::get('/{id}', [LeaveController::class, 'show']);           // GET /api/leaves/{id}
    Route::put('/{id}', [LeaveController::class, 'update']);         // PUT /api/leaves/{id}
    Route::delete('/{id}', [LeaveController::class, 'destroy']);     // DELETE /api/leaves/{id}
});

// Special route for creating leave for employee
Route::post('/leaves/employees/{employeeId}', [LeaveController::class, 'store']); // POST /api/leaves/employees/{employeeId}


?>

