<?php

use App\Http\Controllers\ProjectAssignmentController;
use App\Http\Controllers\Api\DropdownController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('project-assignments.create');
});

Route::resource('project-assignments', ProjectAssignmentController::class);

// API Routes for cascading dropdowns
Route::get('/api/teams/{departmentId}', [DropdownController::class, 'getTeams'])
    ->where('departmentId', '[0-9]+')
    ->name('api.teams');

Route::get('/api/projects/{teamId}', [DropdownController::class, 'getProjects'])
    ->where('teamId', '[0-9]+')
    ->name('api.projects');