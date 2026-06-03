<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\ResendVerificationController;



// Auth Routes
Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);
Route::post('/logout', LogoutController::class)->middleware('auth:sanctum');

// Email Verification Routes
Route::prefix('email')->group(function () {
    Route::post('/verification-notification', ResendVerificationController::class)->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');
    Route::get('/verify/{id}/{hash}', VerifyEmailController::class)->middleware('signed')->name('verification.verify');
});
