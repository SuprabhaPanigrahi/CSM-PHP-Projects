<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', [UserController::class, 'index'])->name('users.index');
Route::post('/users', [UserController::class, 'store'])->name('users.store');

Route::prefix('employees')->group(function () {
    Route::get('/data', [EmployeeController::class, 'showCombinedData'])->name('employee.data');
    Route::post('/add', [EmployeeController::class, 'addEmployee'])->name('employee.add');
});