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

Route::controller(AppointmentController::class)->group(function() {
    Route::get('/dashboard/appointments', 'index');
    // Step one
    Route::get('/dashboard/appointments/create-step-one', 'createStepOne')->name('dashboard.appointments.create.step.one');
    Route::post('/dashboard/appointments/create-step-one', 'createPostStepOne')->name('dashboard.appointments.create.step.one.post');
    // Step two
    Route::get('/dashboard/appointments/create-step-two', 'createStepTwo')->name('dashboard.appointments.create.step.two');
    Route::post('/dashboard/appointments/create-step-two', 'createPostStepTwo')->name('dashboard.appointments.create.step.two.post');
    // Step three
    Route::get('/dashboard/appointments/create-step-three', 'createStepThree')->name('dashboard.appointments.create.step.three');
    Route::post('/dashboard/appointments/create-step-three', 'createPostStepThree')->name('dashboard.appointments.create.step.three.post');
});
