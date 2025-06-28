<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\Dashboard\CompanyController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\CheckSubscription;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Index
Route::get('/', [HomeController::class, 'index'])->name('home');

// Onboarding
Route::controller(OnboardingController::class)->prefix('onboarding')->name('onboarding.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('welcome', 'welcome')->name('welcome');
    Route::get('account-type', 'accountType')->name('account-type');
    Route::post('account-type', 'storeAccountType')->name('account-type.store');
    Route::get('individual/setup', 'individualSetup')->name('individual.setup');
    Route::post('individual/setup', 'storeIndividual')->name('individual.setup.store');
    Route::get('company/setup', 'companySetup')->name('company.setup');
    Route::post('company/setup', 'storeCompany')->name('company.setup.store');
    Route::get('company/team', 'companyTeam')->name('company.team');
    Route::post('company/team', 'storeCompanyTeam')->name('company.team.store');
    Route::get('complete', 'complete')->name('complete');
    Route::get('skip', 'skip')->name('skip');
});

// Pricing & Subscription
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
Route::prefix('subscription')->name('subscription.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [App\Http\Controllers\SubscriptionController::class, 'index'])->name('index');
    Route::post('/checkout', [App\Http\Controllers\SubscriptionController::class, 'checkout'])->name('checkout');
    Route::get('/success', [App\Http\Controllers\SubscriptionController::class, 'success'])->name('success');
    Route::get('/billing', [App\Http\Controllers\SubscriptionController::class, 'billing'])->name('billing');
    
    // Stripe Connect routes
    Route::get('/stripe-connect', [App\Http\Controllers\SubscriptionController::class, 'stripeConnect'])->name('stripe-connect');
    Route::post('/stripe-connect/create', [App\Http\Controllers\SubscriptionController::class, 'createStripeConnect'])->name('stripe-connect.create');
    Route::get('/stripe-connect/return', [App\Http\Controllers\SubscriptionController::class, 'stripeConnectReturn'])->name('stripe-connect.return');
    Route::get('/stripe-connect/refresh', [App\Http\Controllers\SubscriptionController::class, 'stripeConnectRefresh'])->name('stripe-connect.refresh');
    Route::delete('/stripe-connect/disconnect', [App\Http\Controllers\SubscriptionController::class, 'disconnectStripeConnect'])->name('stripe-connect.disconnect');
});

// Public Company Invitation Routes (No Auth Required)
Route::get('/dashboard/companies/{company}/members/accept', [App\Http\Controllers\Dashboard\CompanyMemberController::class, 'showAcceptInvite'])->name('dashboard.companies.members.accept');

Route::prefix('dashboard')->middleware(['auth', 'verified'])->group(function () {
    // Dashboard Home
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

  // Dashboard settings
  Route::get('settings', [SettingsController::class, 'index'])->name('settings');
  Route::put('settings', [SettingsController::class, 'store'])->name('store');

  // Profile
  Route::controller(ProfileController::class)->group(function () {
      Route::get('profile/edit', 'edit')->name('dashboard.profile.edit');
      Route::put('profile/update', 'update')->name('dashboard.profile.update');
      Route::delete('profile/destroy', 'destroy')->name('dashboard.profile.destroy');
  });

  // Companies
  Route::prefix('companies')->name('dashboard.companies.')->controller(CompanyController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('{company}', 'show')->name('show');
    Route::get('{company}/edit', 'edit')->name('edit');
    Route::put('{company}', 'update')->name('update');
    Route::delete('{company}/destroy', 'destroy')->name('destroy');
  });

  // Current User's Company Management
  Route::prefix('company')->name('dashboard.company.')->group(function () {
    Route::get('/', [CompanyController::class, 'currentUserCompany'])->name('index');
    Route::prefix('members')->name('members.')->controller(App\Http\Controllers\Dashboard\CompanyMemberController::class)->group(function () {
      Route::get('/', 'currentUserCompanyMembers')->name('index');
      Route::post('/invite', 'currentUserCompanyInvite')->name('invite');
      Route::patch('/{member}/role', 'currentUserCompanyUpdateRole')->name('update-role');
      Route::delete('/{member}', 'currentUserCompanyRemove')->name('destroy');
    });
    Route::prefix('calendar')->name('calendar.')->controller(App\Http\Controllers\Dashboard\CompanyCalendarController::class)->group(function () {
      Route::get('/', 'index')->name('index');
      Route::get('/date/{date}', 'showDate')->name('date');
    });
  });

  // Company Members (Auth Required)
  Route::prefix('companies/{company}/members')->name('dashboard.companies.members.')->controller(App\Http\Controllers\Dashboard\CompanyMemberController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/invite', 'invite')->name('invite');
    Route::post('/accept', 'acceptInvite')->name('accept.submit');
    Route::delete('/leave', 'leave')->name('leave');
    Route::patch('/{member}/role', 'updateRole')->name('update-role');
    Route::delete('/{member}', 'remove')->name('remove');
  });
  // Appointments
  Route::prefix('appointments')->name('dashboard.appointments.')->middleware('check.subscription')->controller(AppointmentController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('{appointment}/edit', 'edit')->name('edit');
    Route::get('create-step-one', 'createStepOne')->name('create.step.one');
    Route::get('create-step-two', 'createStepTwo')->name('create.step.two');
    Route::get('create-step-three', 'createStepThree')->name('create.step.three');
    
    Route::post('create-step-one', 'createPostStepOne')->name('create.step.one.post');
    Route::post('create-step-two', 'createPostStepTwo')->name('create.step.two.post');
    Route::post('create-step-three', 'createPostStepThree')->name('create.step.three.post');
    
    Route::put('{appointment}', 'update')->name('update');
    Route::patch('{appointment}/approve', 'approve')->name('approve');
    
    Route::delete('{appointment}/destroy', 'destroy')->name('destroy');
  });

  // Legacy billing redirect (keep for backward compatibility)
  Route::get('/billing', function (Request $request) {
    return redirect()->route('subscription.billing');
  })->name('billing');
});

// Company Public Booking Pages - Must be at the end to catch company URLs
Route::get('/{companyUrl}', [App\Http\Controllers\CompanyPublicController::class, 'show'])
    ->where('companyUrl', '[a-z0-9-]+') // Only match valid company URL patterns
    ->name('company.public');

Route::post('/{companyUrl}', [App\Http\Controllers\CompanyPublicController::class, 'processBooking'])
    ->where('companyUrl', '[a-z0-9-]+')
    ->name('company.public.booking.submit');

require __DIR__.'/auth.php';
