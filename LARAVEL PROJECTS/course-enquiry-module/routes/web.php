<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnquiryController;

// 1. Home Route
Route::get('/', [HomeController::class, 'index'])->name('home');

// 2. Courses Routes
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');

// 3. Enquiry Routes
Route::get('/enquiry', [EnquiryController::class, 'create'])->name('enquiry.create');
Route::post('/enquiry', [EnquiryController::class, 'store'])->name('enquiry.store');
Route::get('/enquiry/thank-you', [EnquiryController::class, 'thankYou'])->name('enquiry.thankyou');

// 4. Fallback Route (for 404 pages)
Route::fallback(function () {
    return view('errors.custom-404');
});