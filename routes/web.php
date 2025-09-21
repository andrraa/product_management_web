<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function() {
    Route::controller(AuthController::class)->group(function() {
        Route::get('login', 'index');
        Route::post('login', 'login')->name('login');
    });
});

Route::middleware('auth')->group(function() {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('product', ProductController::class)->except('show');

    Route::middleware(AdminMiddleware::class)->group(function() {
        Route::resource('user', UserController::class);
        
        Route::controller(UserController::class)->group(function() {
            Route::get('user/password/{user}/edit', 'editPassword')->name('user.edit.password');
            Route::put('user/password/{user}', 'updatePassword')->name('user.update.password');
        });
    });

    Route::post('checkout', [CheckoutController::class, 'checkout'])->name('checkout');

    Route::get('history', [HistoryController::class, 'index'])->name('history');
});
