<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{RegisterController, LoginController, LogoutController, VerifyEmailController, ResendVerificationController};
use App\Http\Controllers\Api\User\{UserGetProfileController, UserUpdateProfileController};


// Auth Routes
Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);
Route::post('/logout', LogoutController::class)->middleware('auth:sanctum');

// Email Verification Routes
Route::prefix('email')->group(function () {
    Route::post('/verification-notification', ResendVerificationController::class)->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');
    Route::get('/verify/{id}/{hash}', VerifyEmailController::class)->middleware('signed')->name('verification.verify');
});

Route::middleware('auth:sanctum')->group(function () {

    // User Profile Routes
    Route::get('/profile', UserGetProfileController::class);
    Route::patch('/profile/update', UserUpdateProfileController::class);

});
