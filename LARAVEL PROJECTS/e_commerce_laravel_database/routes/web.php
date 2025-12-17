<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminController;

use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminSliderController;
use App\Http\Controllers\AdminOrderController;

// Home page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth routes
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Product routes
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/category/{id}', [ProductController::class, 'category'])->name('category');

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::get('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Checkout routes (protected by session check in controller)
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.place');
Route::get('/checkout/success/{id}', [CheckoutController::class, 'success'])->name('checkout.success');




// ========= ADMIN ROUTES =========
// Dashboard
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

/// Categories routes
Route::get('/admin/categories', [AdminCategoryController::class, 'index'])->name('admin.categories');
Route::get('/admin/categories/create', [AdminCategoryController::class, 'create'])->name('admin.categories.create');
Route::post('/admin/categories', [AdminCategoryController::class, 'store'])->name('admin.categories.store');
Route::get('/admin/categories/{id}/edit', [AdminCategoryController::class, 'edit'])->name('admin.categories.edit');
Route::post('/admin/categories/{id}', [AdminCategoryController::class, 'update'])->name('admin.categories.update');
Route::get('/admin/categories/{id}/delete', [AdminCategoryController::class, 'destroy'])->name('admin.categories.delete');

// Products routes
Route::get('/admin/products', [AdminProductController::class, 'index'])->name('admin.products');
Route::get('/admin/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
Route::post('/admin/products', [AdminProductController::class, 'store'])->name('admin.products.store');
Route::get('/admin/products/{id}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
Route::post('/admin/products/{id}', [AdminProductController::class, 'update'])->name('admin.products.update');
Route::get('/admin/products/{id}/delete', [AdminProductController::class, 'destroy'])->name('admin.products.delete');

// Sliders
Route::get('/admin/sliders', [AdminSliderController::class, 'index'])->name('admin.sliders');
Route::get('/admin/sliders/create', [AdminSliderController::class, 'create'])->name('admin.sliders.create');
Route::post('/admin/sliders', [AdminSliderController::class, 'store'])->name('admin.sliders.store');
Route::get('/admin/sliders/{id}/edit', [AdminSliderController::class, 'edit'])->name('admin.sliders.edit');
Route::post('/admin/sliders/{id}', [AdminSliderController::class, 'update'])->name('admin.sliders.update');
Route::get('/admin/sliders/{id}/delete', [AdminSliderController::class, 'destroy'])->name('admin.sliders.delete');

// Orders
Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders');
Route::get('/admin/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
Route::post('/admin/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.status');
