<?php

use App\Http\Controllers\Api\Fnb\AuthController;
use App\Http\Controllers\Api\Fnb\CategoryController;
use App\Http\Controllers\Api\Fnb\LoginController;
use App\Http\Controllers\Api\Fnb\OutletController;
use App\Http\Controllers\Api\Fnb\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('fnb/login', [AuthController::class, 'login']);
Route::post('fnb/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('fnb/outlets', [OutletController::class, 'index'])->middleware(['auth:sanctum']);

Route::prefix('fnb')->middleware(['auth:sanctum', 'fnb.outlet'])->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);

    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
    });

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
    });
});
