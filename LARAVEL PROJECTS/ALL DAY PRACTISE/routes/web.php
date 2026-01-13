<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function(){
    return view('welcome');
});

Route::get('/students/create', [StudentController::class, 'create'])->name('student.create');

Route::post('/students/save', [StudentController::class, 'save'])->name('student.save');

Route::get('/students', [StudentController::class, 'view'])->name('student.view');

Route::get('/students/fetch', [StudentController::class, 'fetch'])->name('student.fetch');

