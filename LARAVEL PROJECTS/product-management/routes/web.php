<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

// Resource route
Route::resource('products', ProductController::class);

// AJAX cascading dropdown route
Route::get('/get-subcategories/{category_id}', 
    [ProductController::class, 'getSubcategories']
);
