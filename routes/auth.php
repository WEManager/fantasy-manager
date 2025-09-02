<?php

declare(strict_types=1);

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [Controllers\Auth\RegisteredUserController::class, 'create'])->name('register');

    Route::post('register', [Controllers\Auth\RegisteredUserController::class, 'store']);

    Route::get('login', [Controllers\Auth\AuthenticatedSessionController::class, 'create'])->name('login');

    Route::post('login', [Controllers\Auth\AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [Controllers\Auth\PasswordResetLinkController::class, 'create'])->name('password.request');

    Route::post('forgot-password', [Controllers\Auth\PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::post('reset-password', [Controllers\Auth\NewPasswordController::class, 'store'])->name('password.update');

    Route::get('reset-password/{token}', [Controllers\Auth\NewPasswordController::class, 'create'])->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', [Controllers\Auth\EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', [Controllers\Auth\VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [Controllers\Auth\EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [Controllers\Auth\ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [Controllers\Auth\ConfirmablePasswordController::class, 'store']);

    Route::post('logout', [Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
