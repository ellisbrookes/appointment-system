<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Dashboard\CompanyController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\CheckSubscription;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Index
Route::get('/', [HomeController::class, 'index'])->name('home');

// Pricing
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');

Route::prefix('dashboard')->middleware(['auth', 'verified'])->group(function () {
    // Dashboard Home
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

  // Dashboard settings
  Route::get('settings', [SettingsController::class, 'index'])->name('settings');
  Route::put('settings', [SettingsController::class, 'store'])->name('store');

  // Companies
  Route::prefix('companies')->name('dashboard.companies.')->controller(CompanyController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::put('{company}', 'update')->name('update');
    Route::delete('{company}/destroy', 'destroy')->name('destroy');
  });

  // Appointments
  Route::prefix('appointments')->name('dashboard.appointments.')->controller(AppointmentController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('{appointment}/edit', 'edit')->name('edit');
    Route::get('create-step-one', 'createStepOne')->name('create.step.one');
    Route::get('create-step-two', 'createStepTwo')->name('create.step.two');
    Route::get('create-step-three', 'createStepThree')->name('create.step.three');
    
    Route::post('create-step-one', 'createPostStepOne')->name('create.step.one.post');
    Route::post('create-step-two', 'createPostStepTwo')->name('create.step.two.post');
    Route::post('create-step-three', 'createPostStepThree')->name('create.step.three.post');
    
    Route::put('{appointment}', 'update')->name('update');
    
    Route::delete('{appointment}/destroy', 'destroy')->name('destroy');
  });

  // Billing
  Route::get('/billing', function (Request $request) {
    return $request->user()->redirectToBillingPortal(route('dashboard'));
  })->name('billing');

  Route::post('/subscription-checkout', function (Request $request) {
    return $request->user()
      ->newSubscription('basic', 'price_1QbtKfGVcskF822y3QlF13vZ')
      ->allowPromotionCodes()
      ->checkout([
        'success_url' => route('dashboard'),
        'cancel_url' => route('dashboard'),
      ]);
  })->name('subscription');
});

require __DIR__.'/auth.php';
