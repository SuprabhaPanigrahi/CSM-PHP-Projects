<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FormController;

Route::prefix('form')->group(function () {
    Route::post('/submit', [FormController::class, 'submit']);
    Route::get('/submissions', [FormController::class, 'index']);
    Route::get('/submission/{id}', [FormController::class, 'show']);
    Route::delete('/submission/{id}', [FormController::class, 'destroy']);
});     