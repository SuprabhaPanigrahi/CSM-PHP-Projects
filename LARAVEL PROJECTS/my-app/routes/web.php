<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('layouts/home');
})->name('home');

Route::get('/about', function () {
    return view('layouts/about');
})->name('about');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');

Route::get('/contact', function () {
    return view('layouts/contact');
})->name('contact');
