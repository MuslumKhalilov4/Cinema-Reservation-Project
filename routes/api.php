<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{RegisterController, LoginController, LogoutController, VerifyEmailController, ResendVerificationController};
use App\Http\Controllers\Admin\{GenreController, ActorController};
use App\Http\Controllers\Api\User\{UserGetProfileController, UserUpdateProfileController, UserChangePasswordController, UserChangeAvatarController, UserDeleteAccountController};


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
    Route::prefix('profile')->group(function () {
        Route::get('/', UserGetProfileController::class);
        Route::patch('/update', UserUpdateProfileController::class);
        Route::put('/change-password', UserChangePasswordController::class);
        Route::put('/change-avatar', UserChangeAvatarController::class);
        Route::delete('/delete', UserDeleteAccountController::class);
    });
});

// Admin Routes
Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::middleware(['role:admin|super_admin', 'verified'])->group(function () {
        Route::apiResource('genres', GenreController::class);
    });
});

Route::prefix('admin')->group(function () {
    Route::apiResource('actors', ActorController::class);
});

