<?php

use App\Http\Controllers\Api\Fnb\AuthController;
use App\Http\Controllers\Api\Fnb\CategoryController;
use App\Http\Controllers\Api\Fnb\CouponController;
use App\Http\Controllers\Api\Fnb\OutletController;
use App\Http\Controllers\Api\Fnb\ProductController;
use App\Http\Controllers\Api\Fnb\SaleController;
use App\Http\Controllers\Api\Fnb\TransactionController;
use App\Http\Controllers\Api\Fnb\UtilityController;
use Illuminate\Support\Facades\Route;

Route::post('fnb/login', [AuthController::class, 'login']);
Route::post('fnb/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('fnb/outlets-list', [OutletController::class, 'index'])->middleware(['auth:sanctum']);

Route::prefix('fnb')->middleware(['auth:sanctum', 'fnb.outlet'])->group(function () {
    Route::get('/outlet', [OutletController::class, 'show']);
    Route::get('/profile', [AuthController::class, 'profile']);

    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
    });

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
    });

    Route::prefix('coupons')->group(function () {
        Route::get('/', [CouponController::class, 'index']);
        Route::get('/{code}', [CouponController::class, 'show']);
        Route::post('/{code}/redeem', [CouponController::class, 'redeem']);
    });

    Route::prefix('utilities')->group(function () {
        Route::get('/sale-mode', [UtilityController::class, 'saleMode']);
        Route::get('/table', [UtilityController::class, 'table']);
        Route::get('/tax', [UtilityController::class, 'tax']);
    });

    Route::prefix('transaction')->group(function () {
        Route::post('/process', [TransactionController::class, 'process']);
        Route::post('/{id}/send-invoice', [TransactionController::class, 'sendInvoice']);
    });

    Route::prefix('sales')->group(function () {
        Route::get('/', [SaleController::class, 'index']);
        Route::get('/{id}', [SaleController::class, 'show']);
    });

});
