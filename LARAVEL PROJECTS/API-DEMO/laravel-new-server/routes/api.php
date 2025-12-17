<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController; // Add this line
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function(){
    return 'Welcome to server';
});

// Product Routes
Route::get('/products', [ProductController::class, 'index']);
Route::post('/product/create', [ProductController::class, 'store']);
Route::put('/product/{id}', [ProductController::class, 'update']);
Route::delete('/product/{id}', [ProductController::class, 'delete']);

// Category Routes (Add these)
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/category/create', [CategoryController::class, 'store']);
Route::get('/category/{id}', [CategoryController::class, 'show']);
Route::put('/category/{id}', [CategoryController::class, 'update']);
Route::delete('/category/{id}', [CategoryController::class, 'destroy']);