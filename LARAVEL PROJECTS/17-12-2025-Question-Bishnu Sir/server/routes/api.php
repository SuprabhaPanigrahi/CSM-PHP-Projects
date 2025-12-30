<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\MenuController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware(['auth.api'])->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // Menu routes
    Route::get('/menu', [MenuController::class, 'getMenu']);
    Route::post('/check-access', [MenuController::class, 'checkAccess']);
    
    // Products - accessible by all (validated via JWT role)
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);

    // Offers - only for diamond customers
    Route::get('/offers', [OfferController::class, 'index'])->middleware('jwt.role:diamond');

    // Purchases - only for gold and diamond customers
    Route::post('/purchase', [PurchaseController::class, 'store'])->middleware('jwt.role:gold,diamond');
    Route::get('/purchases/history', [PurchaseController::class, 'history'])->middleware('jwt.role:gold,diamond');
});