<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;

Route::get('/', [ClientController::class, 'login'])->name('login');
Route::get('/register', [ClientController::class, 'register'])->name('register');
Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
Route::get('/products', [ClientController::class, 'products'])->name('products');
Route::get('/offers', [ClientController::class, 'offers'])->name('offers');
Route::get('/purchase', [ClientController::class, 'purchase'])->name('purchase');
Route::get('/purchase-history', [ClientController::class, 'purchaseHistory'])->name('purchase.history');

// Error pages
Route::get('/error/403', function() {
    return view('errors.403');
})->name('error.403');

Route::get('/error/401', function() {
    return view('errors.401');
})->name('error.401');

// API routes for client to manage session
Route::post('/set-session', [ClientController::class, 'setSession']);
Route::post('/clear-session', [ClientController::class, 'clearSession']);