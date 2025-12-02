<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeaveApplicationController;

// Home page
Route::get('/', [LeaveApplicationController::class, 'home']);

// Leave Application Routes
Route::get('/leave', [LeaveApplicationController::class, 'index'])->name('leave.index');
Route::get('/leave/create', [LeaveApplicationController::class, 'create'])->name('leave.create');
Route::post('/leave', [LeaveApplicationController::class, 'store'])->name('leave.store');

// Edit, Update, Delete
Route::get('/leave/{id}/edit', [LeaveApplicationController::class, 'edit'])->name('leave.edit');
Route::put('/leave/{id}', [LeaveApplicationController::class, 'update'])->name('leave.update');
Route::delete('/leave/{id}', [LeaveApplicationController::class, 'destroy'])->name('leave.destroy');


Route::get('/leave/employees/{departmentId}', [LeaveApplicationController::class, 'getEmployees']);
Route::get('/leave/leave-types/{employeeId}', [LeaveApplicationController::class, 'getLeaveTypes']);
Route::get('/leave/employee-details/{employeeId}', [LeaveApplicationController::class, 'getEmployeeDetails']);
Route::post('/leave/calculate-days', [LeaveApplicationController::class, 'calculateDays']);
