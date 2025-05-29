<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Auth\PasswordForgotController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\EmailVerificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
  // Register
  Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
  Route::post('register', [RegisteredUserController::class, 'store']);

  // Login
  Route::get('login', [SessionController::class, 'create'])->name('login');
  Route::post('login', [SessionController::class, 'store']);

  // Forgot Password
  Route::get('forgot-password', [PasswordForgotController::class, 'create'])->name('password.request');
  Route::post('forgot-password', [PasswordForgotController::class, 'store'])->name('password.email');

  // Reset Password
  Route::get('reset-password/{token}', [PasswordResetController::class, 'create'])->name('password.reset');
  Route::post('reset-password', [PasswordResetController::class, 'store'])->name('password.store');

  // Email Verification
  Route::middleware(['auth'])->group(function () {
    // Verification Notice
    Route::get('email/verify', [EmailVerificationController::class, 'notice'])
      ->name('verification.notice');

    // Verify email callback
    Route::get('email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
      ->middleware(['signed', 'throttle:6,1'])
      ->name('verification.verify');

    // Resend verification email
    Route::post('email/verification-notification', [EmailVerificationController::class, 'resend'])
      ->middleware(['throttle:6,1'])
      ->name('verification.send');
  });

  // Logout
  Route::post('logout', [SessionController::class, 'destroy'])->name('logout');
});
