<?php

use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

Route::middleware('guest')->group(function () {
  Route::get('register', [Controllers\Auth\RegisteredUserController::class, 'create'])->name('register');
  Route::post('register', [Controllers\Auth\RegisteredUserController::class, 'store']);

  Route::get('login', [Controllers\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
  Route::post('login', [Controllers\Auth\AuthenticatedSessionController::class, 'store']);
  Route::post('logout', [Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

  Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
  Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

  Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');
  Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
});

Route::middleware('auth')->group(function () {
  Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');

  Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

  Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('verification.send');

  Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
    ->name('password.confirm');

  Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
});
