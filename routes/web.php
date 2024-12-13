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

// Route::get('/appointments', [
//     AppointmentController::class, 'index'
// ])->name('appointments.view');

Route::get('dashboard/appointments', [AppointmentController::class, 'index'])->name('appointments.index');

// For displaying the form with the calendar
Route::get('dashboard/appointments/create-step-one', [AppointmentController::class, 'createStepOne'])->name('appointments.create.step.one');

// For handling the form submission
Route::post('dashboard/appointments/create-step-one', [AppointmentController::class, 'postCreateStepOne'])->name('appointments.create.step.one.post');

// Route::get('appointments/create-step-two', 'AppointmentController@createStepTwo')->name('appointments.create.step.two');
// Route::post('appointments/create-step-two', 'AppointmentController@postCreateStepTwo')->name('appointments.create.step.two.post');

// Route::get('appointments/create-step-three', 'AppointmentController@createStepThree')->name('appointments.create.step.three');
// Route::post('appointments/create-step-three', 'AppointmentController@postCreateStepThree')->name('appointments.create.step.three.post');
