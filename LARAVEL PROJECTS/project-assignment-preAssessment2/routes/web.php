<?php

use App\Http\Controllers\ProjectAssignmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('project-assignments.index');
});

Route::resource('project-assignments', ProjectAssignmentController::class);

Route::get('/api/teams/{departmentId}', [App\Http\Controllers\Api\DropdownController::class, 'getTeams']);
Route::get('/api/projects/{teamId}', [App\Http\Controllers\Api\DropdownController::class, 'getProjects']);