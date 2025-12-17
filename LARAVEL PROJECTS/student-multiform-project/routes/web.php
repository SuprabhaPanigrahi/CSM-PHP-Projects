<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;

Route::get('/', [FormController::class, 'step1'])->name('form.step1');
Route::post('/step1', [FormController::class, 'postStep1'])->name('form.postStep1');

Route::get('/step2', [FormController::class, 'step2'])->name('form.step2');
Route::post('/step2', [FormController::class, 'postStep2'])->name('form.postStep2');

Route::get('/step3', [FormController::class, 'step3'])->name('form.step3');
Route::post('/submit', [FormController::class, 'submit'])->name('form.submit');

Route::get('/clear', [FormController::class, 'clear'])->name('form.clear');