<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
  // Register
  Route::get('register', [RegisteredUserController::class, 'create'])->name('auth.register');
  Route::post('register', [RegisteredUserController::class, 'store']);
});
