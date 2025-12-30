<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.registration');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('auth.dashboard');
});