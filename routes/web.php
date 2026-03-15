<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Back\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Back\Admin\UserController as AdminUserController;
use App\Http\Controllers\Back\businessController;
use App\Http\Controllers\front\HomeController;
use App\Http\Controllers\language\LanguageController;
use Illuminate\Support\Facades\Route;

// Main Page Route
Route::get('/', [HomeController::class, 'index'])->name('home');

// locale
Route::get('/lang/{locale}', [LanguageController::class, 'swap']);

// authentication
Route::prefix('auth')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::redirect('/login', '/auth/login');
        Route::redirect('/register', '/auth/register');

        Route::get('/login', [LoginController::class, 'index'])->name('login');
        Route::post('/login', [LoginController::class, 'store'])->name('auth.login.store');

        Route::get('/register', [RegisterController::class, 'index'])->name('register');
        Route::post('/register', [RegisterController::class, 'store'])->name('auth.register.store');

        Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
        Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');

        Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
        Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('password.store');

        Route::view('/verify-email', 'content.auth.verify-email', ['pageConfigs' => ['myLayout' => 'blank']])
            ->name('auth.verify-email');
        Route::view('/two-steps', 'content.auth.two-steps', ['pageConfigs' => ['myLayout' => 'blank']])
            ->name('auth.two-steps');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    });
});

Route::prefix('business')->name('business.')->middleware('auth')->group(function () {
    Route::get('/', [businessController::class, 'index'])->name('index');
    Route::post('/store', [businessController::class, 'store'])->name('store');
});

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::redirect('/', '/admin/dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', AdminUserController::class);
});
