<?php

use App\Http\Controllers\CitizenController;
use Illuminate\Support\Facades\Route;

// Make the citizen registration form the homepage
Route::get('/', [CitizenController::class, 'create'])->name('home');

// Citizen Routes
Route::get('/citizens', [CitizenController::class, 'index'])->name('citizens.index');
Route::get('/citizens/create', [CitizenController::class, 'create'])->name('citizens.create');
Route::post('/citizens', [CitizenController::class, 'store'])->name('citizens.store');
Route::get('/citizens/{id}/edit', [CitizenController::class, 'edit'])->name('citizens.edit');
Route::put('/citizens/{id}', [CitizenController::class, 'update'])->name('citizens.update');
Route::delete('/citizens/{id}', [CitizenController::class, 'destroy'])->name('citizens.destroy');