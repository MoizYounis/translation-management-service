<?php

use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TranslationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function () {
    // Login
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->group(function () {
    // Translation CRUD api routes
    Route::apiResource('translations', TranslationController::class);

    Route::controller(TranslationController::class)->group(function () {
        // JSON export endpoint
        Route::get('translations-export', 'export');
    });

    Route::controller(AuthController::class)->group(function () {
        // logout
        Route::post('logout', 'logout');
    });
});
