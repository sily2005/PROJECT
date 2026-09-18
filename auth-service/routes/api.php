<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Authentication Routes
|--------------------------------------------------------------------------
*/
$authRoutes = function (): void {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
};

// Hỗ trợ cả 2 đường dẫn trực tiếp và có tiền tố /auth
$authRoutes();
Route::prefix('auth')->group($authRoutes);

/*
|--------------------------------------------------------------------------
| Protected Routes (Bearer Token via auth:api)
|--------------------------------------------------------------------------
*/
$protectedRoutes = function (): void {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::patch('/profile', [AuthController::class, 'updateProfile']);

    // Address Management
    Route::get('/addresses', [AddressController::class, 'index']);
    Route::post('/addresses', [AddressController::class, 'store']);
    Route::put('/addresses/{address}', [AddressController::class, 'update']);
    Route::patch('/addresses/{address}', [AddressController::class, 'update']);
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy']);
    Route::patch('/addresses/{address}/set-default', [AddressController::class, 'setDefault']);
    Route::patch('/addresses/{address}/default', [AddressController::class, 'setDefault']);
};

Route::middleware('auth:api')->group($protectedRoutes);
Route::prefix('auth')->middleware('auth:api')->group($protectedRoutes);

/*
|--------------------------------------------------------------------------
| User Management Routes (Admin & Gateway support)
|--------------------------------------------------------------------------
*/
Route::get('/users', [AuthController::class, 'getUsers']);
Route::patch('/users/{user}/status', [AuthController::class, 'updateUserStatus']);
Route::patch('/profile', [AuthController::class, 'updateProfile']);
Route::prefix('auth')->group(function (): void {
    Route::get('/users', [AuthController::class, 'getUsers']);
    Route::patch('/users/{user}/status', [AuthController::class, 'updateUserStatus']);
    Route::patch('/profile', [AuthController::class, 'updateProfile']);
});
