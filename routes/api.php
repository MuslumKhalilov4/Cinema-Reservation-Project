<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Http\Request;
use App\Models\User;

Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);
Route::post('/logout', LogoutController::class)->middleware('auth:sanctum');






Route::post('email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return response()->json([
        'message' => 'Verification link sent to your email address',
    ], 200);
})->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');

Route::get('email/verify', function () {
    return response()->json([
        'message' => 'Please verify your email address',
    ], 403);
})->middleware('auth:sanctum')->name('verification.notice');

Route::get('email/verify/{id}/{hash}', function (int $id, string $hash) {
    $user = User::find($id);

    if (!$user || !hash_equals($hash, sha1($user->getEmailForVerification()))) {
        return response()->json([
            'message' => 'Invalid verification link',
        ], 404);
    }

    if ($user->hasVerifiedEmail()) {
        return response()->json([
            'message' => 'Email already verified',
        ], 400);
    }

    $user->markEmailAsVerified();

    return response()->json([
        'message' => 'Email verified successfully',
    ], 200);
})->middleware('signed')->name('verification.verify');
