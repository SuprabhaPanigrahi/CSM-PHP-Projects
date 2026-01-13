<?php

use App\Http\Controllers\Api\VisitFileController as ApiVisitFileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\TestsController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\VisitFileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function () {
    return "API is working";
});

Route::middleware(['jwt.auth'])->group(function () {

    Route::get('/departments', [DepartmentController::class, 'departments'])->name('departments');

    Route::get('/categories', [CategoryController::class, 'categories'])->name('categories');

    Route::get('/tests', [TestsController::class, 'tests'])->name('tests');

    Route::get('/departments/{department}/categories', [CategoryController::class, 'byDepartment']);

    Route::get('/categories/{category}/tests', [TestsController::class, 'byCategory']);

    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');

    Route::get('/patients', [PatientController::class, 'view'])->name('patients.view');

    Route::get('/patients/{id}', [PatientController::class, 'viewById'])->name('patients.viewById');

    Route::post('/visits', [VisitController::class, 'store'])->name('patients.visit');

    Route::get('/visits/{patientId}', [VisitController::class, 'indexByPatient']);

    Route::post('/visits/{visitId}/tests', [VisitController::class, 'addTests']);

    Route::post('/visits/{visitId}/files', [VisitFileController::class, 'store'])->name('file.upload');

    Route::get('/visits/{visitId}/files', [VisitFileController::class, 'index']);

    Route::get('/files/{fileId}/download', [VisitFileController::class, 'download']);

    Route::get('/visit-summary', [VisitFileController::class, 'visitSummary']);

    Route::get('/visits/{id}/full', [VisitController::class, 'showFull']);

});

Route::post('/auth/register', [AuthController::class, 'register'])->name('register');

Route::post('/auth/login', [AuthController::class, 'login'])->name('login');
