<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('test-page');
});

Route::get('/dashboard', function () {
    return view('dashboard.index');
});

// Controller-based routes for appointments
Route::get('dashboard/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
Route::post('dashboard/appointments/store', [AppointmentController::class, 'store'])->name('appointments.store');
Route::get('dashboard/appointments/review', [AppointmentController::class, 'review'])->name('appointments.review');