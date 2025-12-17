<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// Health Check Route (Public)
Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is running',
        'version' => '1.0.0',
        'timestamp' => now()->toDateTimeString(),
    ]);
})->name('api.health');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->name('auth.')->group(function () {
    // Public routes (no authentication required)
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    // Protected routes (require JWT authentication)
    Route::middleware(['jwt.auth', 'status'])->group(function () {
        Route::get('/me', [AuthController::class, 'me'])->name('me');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
    });
});

/*
|--------------------------------------------------------------------------
| Product Routes
|--------------------------------------------------------------------------
*/

Route::prefix('products')->name('products.')->group(function () {
    // Public routes
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{id}', [ProductController::class, 'show'])->name('show');
    Route::get('/vendor/{vendorId}', [ProductController::class, 'vendorProducts'])->name('vendor');

    // Protected routes (require authentication)
    Route::middleware(['jwt.auth', 'status'])->group(function () {
        // Vendor and Admin can create products
        Route::post('/', [ProductController::class, 'store'])
            ->middleware('role:vendor,admin')
            ->name('store');

        // Owner (vendor who created) or Admin can update/delete
        Route::put('/{id}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
    });

    // Product Reviews (nested resource)
    Route::prefix('/{productId}/reviews')->name('reviews.')->group(function () {
        // Public route
        Route::get('/', [ReviewController::class, 'index'])->name('index');

        // Protected routes
        Route::middleware(['jwt.auth', 'status'])->group(function () {
            Route::post('/', [ReviewController::class, 'store'])
                ->middleware('role:customer')
                ->name('store');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Review Routes
|--------------------------------------------------------------------------
*/

Route::prefix('reviews')->name('reviews.')->middleware(['jwt.auth', 'status'])->group(function () {
    Route::put('/{id}', [ReviewController::class, 'update'])->name('update');
    Route::delete('/{id}', [ReviewController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Order Routes
|--------------------------------------------------------------------------
*/

Route::prefix('orders')->name('orders.')->middleware(['jwt.auth', 'status'])->group(function () {
    // Customer routes
    Route::get('/', [OrderController::class, 'index'])->name('index');
    Route::post('/', [OrderController::class, 'store'])
        ->middleware('role:customer')
        ->name('store');
    Route::get('/{id}', [OrderController::class, 'show'])->name('show');

    // Vendor and Admin routes
    Route::put('/{id}/status', [OrderController::class, 'updateStatus'])
        ->middleware('role:vendor,admin')
        ->name('update-status');

    // Customer and Admin can cancel orders
    Route::delete('/{id}', [OrderController::class, 'destroy'])->name('cancel');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['jwt.auth', 'status', 'role:admin'])->group(function () {
    Route::get('/users', [AdminController::class, 'getUsers'])->name('users');
    Route::get('/statistics', [AdminController::class, 'getStatistics'])->name('statistics');
});     