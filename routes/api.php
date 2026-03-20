<?php

use App\Http\Controllers\Api\Fnb\AuthController;
use App\Http\Controllers\Api\Fnb\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::prefix('fnb')->middleware('auth:sanctum')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    
});
