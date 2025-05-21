<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompanyController;
use App\Http\Middleware\CheckSubscription;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');

Route::middleware('auth')->group(function () {
    Route::prefix('dashboard')->group(function () {
        
        // Dashboard Home
        Route::get('/', function () {
            return view('dashboard.index');
        })->middleware(['verified', CheckSubscription::class])->name('dashboard');

        // Profile
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'edit')->name('dashboard.profile.edit');
            Route::patch('/profile', 'update')->name('dashboard.profile.update');
            Route::delete('/profile', 'destroy')->name('dashboard.profile.destroy');
        });

        // Company
        Route::prefix('company')->name('dashboard.company.')->group(function () {
            Route::get('/', [CompanyController::class, 'index'])->name('index');
            Route::get('/create', [CompanyController::class, 'create'])->name('create');
            Route::post('/', [CompanyController::class, 'store'])->name('store');
            Route::get('/{company}/edit', [CompanyController::class, 'edit'])->name('edit');
            Route::put('/{company}', [CompanyController::class, 'update'])->name('update');

            Route::get('/{company}/users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/{company}/users', [UserController::class, 'store'])->name('users.store');
        });

        // Appointments
        Route::prefix('appointments')->name('dashboard.appointments.')->controller(AppointmentController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create-step-one', 'createStepOne')->name('create.step.one');
            Route::post('/create-step-one', 'createPostStepOne')->name('create.step.one.post');
            Route::get('/create-step-two', 'createStepTwo')->name('create.step.two');
            Route::post('/create-step-two', 'createPostStepTwo')->name('create.step.two.post');
            Route::get('/create-step-three', 'createStepThree')->name('create.step.three');
            Route::post('/create-step-three', 'createPostStepThree')->name('create.step.three.post');
            Route::get('/{appointment}/edit', 'edit')->name('edit');
            Route::put('/{appointment}', 'update')->name('update');
            Route::delete('/{appointment}/destroy', 'destroy')->name('destroy');
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
});

require __DIR__ . '/auth.php';
