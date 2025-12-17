<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

// Home page
Route::get('/', [ProductController::class, 'home'])->name('home');

// Category page
Route::get('/category/{categoryName}', [ProductController::class, 'category'])->name('category.show');

// Product CRUD routes
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/', [ProductController::class, 'store'])->name('products.store');
    Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
});

// Cart routes
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/count', [CartController::class, 'count'])->name('cart.count');
});