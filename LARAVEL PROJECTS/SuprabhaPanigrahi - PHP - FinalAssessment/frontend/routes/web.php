<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/register', function () {
    return view('register');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/employees', function () {
    return view('employees.index');
});

Route::get('/projects', function () {
    return view('projects.index');
});

Route::get('/assign', function () {
    return view('assign');
});

Route::get('/assignments', function () {
    return view('assignments');
});