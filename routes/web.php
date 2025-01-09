<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\CheckSubscription;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/test', function () {
    return view('test-page');
})->name('test');

Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');

Route::middleware('auth')->group(function () {
    Route::controller(ProfileController::class)->group(function () {

        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->middleware(['verified'])->name('dashboard');

    Route::get('/billing', function (Request $request) {
        return $request->user()->redirectToBillingPortal(route('dashboard'));;
    })->name('billing');


Route::post('/subscription-checkout', function (Request $request) {
    return $request->user()
        ->newSubscription('basic', 'price_1QbtKfGVcskF822y3QlF13vZ')
        ->allowPromotionCodes()
        ->checkout([
            'success_url' => route('dashboard'),
            'cancel_url' => route('welcome'),
        ]);
    })->name('subscription');

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

require __DIR__.'/auth.php';
