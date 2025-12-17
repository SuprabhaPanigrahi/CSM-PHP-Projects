<?php

use Illuminate\Support\Facades\Route;

// Keep only this for web routes (optional)
Route::get('/', function () {
    return response()->json([
        'message' => 'JWT E-Commerce API',
        'version' => '1.0.0',
        'documentation' => '/api/documentation',
    ]);
});