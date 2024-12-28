<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;
use Carbon\Carbon;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('test-page');
});

Route::middleware('auth')->group(function () {
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->middleware('verified')->name('dashboard');

    Route::prefix('dashboard/appointments')->name('dashboard.appointments.')->group(function () {
        Route::controller(AppointmentController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create-step-one', 'createStepOne')->name('create.step.one');
            Route::post('/create-step-one', 'createPostStepOne')->name('create.step.one.post');
            Route::get('/create-step-two', 'createStepTwo')->name('create.step.two');
            Route::post('/create-step-two', 'createPostStepTwo')->name('create.step.two.post');
            Route::get('/create-step-three', 'createStepThree')->name('create.step.three');
            Route::post('/create-step-three', 'createPostStepThree')->name('create.step.three.post');
        });
    });
});

Route::get('/send-test', [EmailController::class, 'sendTestEmail']);
$data = ['message' => 'This is a test!'];

Route::controller(AppointmentController::class)->group(function () {
    // Appointments index route
    Route::get('/dashboard/appointments', 'index')->name('dashboard.appointments.index');

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

require __DIR__.'/auth.php';
