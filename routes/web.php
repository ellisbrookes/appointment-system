<?php

use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use App\Http\Controllers\AppointmentController;

// Existing routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('test-page');
});

Route::get('/dashboard', function() {
    return view('dashboard.index');
});

Route::get('/appointments', [
    AppointmentController::class, 'index'
])->name('appointments.view');
