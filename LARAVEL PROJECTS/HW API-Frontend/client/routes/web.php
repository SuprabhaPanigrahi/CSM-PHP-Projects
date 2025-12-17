<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/form', function () {
    return view('form');
});

Route::get('/submissions', function () {
    return view('submissions');
});