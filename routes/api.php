<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Channel\ChannelController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Public API routes
Route::post('/login', [LoginController::class, 'authenticate'])->name('api.login');
Route::post('/register', [RegisterController::class, 'storeUser'])->name('api.register');

// Google Authentication Routes
Route::get('/auth/google', [GoogleController::class, 'redirectTo'])->name('api.auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'LoginWithGoogle']);

// Protect routes with middleware 'auth:sanctum' (for token authentication)
Route::middleware(['auth:sanctum'])->group(function () {
    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('api.home');
    Route::get('/dashboard', [GoogleController::class, 'index'])->name('api.dashboard');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('api.logout');

    // Channel Resource Routes
    Route::apiResource('/channels', ChannelController::class);

    // Password Reset Routes
    Route::post('/forget-password', [ForgotPasswordController::class, 'postEmail'])->name('api.forget-password');
    Route::post('/reset-password', [ResetPasswordController::class, 'updatePassword'])->name('api.reset-password');
});
