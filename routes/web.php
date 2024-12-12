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

Route::get('/dashboard/appointments', function() {
    $currentDate = Carbon::now();
    $daysInMonth = $currentDate->daysInMonth;
    $firstDayOfMonth = $currentDate->startOfMonth()->dayOfWeek;

    return view('dashboard.appointments.index', [
        'currentDate' => $currentDate,
        'daysInMonth' => $daysInMonth,
        'firstDayOfMonth' => $firstDayOfMonth,
    ]);
});

// New route for the appointments
Route::get('/appointments', [AppointmentController::class, 'showAppointments'])->name('appointments.view');
