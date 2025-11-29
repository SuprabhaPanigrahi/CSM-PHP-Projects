<?php
// routes/web.php

use App\Http\Controllers\ResourceAllocationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ResourceAllocationController::class, 'index']);
Route::get('/departments/{businessUnitId}', [ResourceAllocationController::class, 'getDepartments']);
Route::get('/teams/{departmentId}', [ResourceAllocationController::class, 'getTeams']);
Route::get('/employees', [ResourceAllocationController::class, 'getEmployees']);
Route::post('/allocate', [ResourceAllocationController::class, 'allocateEmployee']);

// Debug routes
Route::post('/debug-allocate', [ResourceAllocationController::class, 'debugAllocate']);
Route::get('/test-allocation', function () {
    // Test if we can create an allocation
    try {
        $allocation = \App\Models\EmployeeProjectAllocation::create([
            'EmployeeId' => 1,
            'ProjectId' => 1,
            'AllocationStartDate' => '2024-01-01',
            'AllocationEndDate' => '2024-12-31',
            'AllocationPercentage' => 100,
            'IsActive' => true,
        ]);

        return "Test allocation created successfully! ID: " . $allocation->AllocationId;
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

// Allocations list
Route::get('/allocations', [ResourceAllocationController::class, 'allocations'])->name('allocations.list');

// Project creation
Route::get('/projects/create', [ResourceAllocationController::class, 'createProject'])->name('project.create');
Route::post('/projects', [ResourceAllocationController::class, 'storeProject'])->name('project.store');

// Update the home route to have a name
Route::get('/', [ResourceAllocationController::class, 'index'])->name('home');

Route::get('/test-data', function () {
    // Check if we have basic data
    $businessUnits = \App\Models\BusinessUnit::count();
    $departments = \App\Models\Department::count();
    $teams = \App\Models\Team::count();
    $employees = \App\Models\Employee::count();
    $projects = \App\Models\Project::count();

    return "
    Data Check:<br>
    Business Units: $businessUnits<br>
    Departments: $departments<br>
    Teams: $teams<br>
    Employees: $employees<br>
    Projects: $projects<br>
    ";
});
