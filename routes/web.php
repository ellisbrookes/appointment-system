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

    // Default selected day is today
    $selectedDay = $currentDate->day;

    // If a selected day is passed in the query, update the selected day
    if (request()->has('selected_day')) {
        $selectedDay = request()->get('selected_day');
    }

    // Get the full date for the selected day
    $fullDate = Carbon::create($currentDate->year, $currentDate->month, $selectedDay)->format('l, F j, Y');

    // Pass the data to the view
    return view('dashboard.appointments.index', [
        'currentDate' => $currentDate,
        'daysInMonth' => $daysInMonth,
        'firstDayOfMonth' => $firstDayOfMonth,
        'selectedDay' => $selectedDay,
        'fullDate' => $fullDate,
    ]);
});

// New route for the appointments view handled by AppointmentController
Route::get('/appointments', [AppointmentController::class, 'showAppointments'])->name('appointments.view');
