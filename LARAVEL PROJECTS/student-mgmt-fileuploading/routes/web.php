<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return redirect('/students');
});

// Student Routes
Route::resource('students', StudentController::class);

// Cascading Dropdown Route
Route::get('/get-states/{country_id}', [StudentController::class, 'getStates']);