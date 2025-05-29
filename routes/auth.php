<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SessionController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
  // Register
  Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
  Route::post('register', [RegisteredUserController::class, 'store']);

  // Login
  Route::get('login', [SessionController::class, 'create'])->name('login');
  Route::post('login', [SessionController::class, 'store']);

  // Logout
  Route::post('logout', [SessionController::class, 'destroy'])->name('logout');
});
