<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\CompanyMember;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
  public function create(Request $request): View {
    $companyInvite = null;
    $prefilledEmail = null;
    
    // Check if user is coming from a company invitation
    if ($request->has('company_invite') && $request->has('email')) {
      $companyInvite = Company::find($request->company_invite);
      $prefilledEmail = $request->email;
      
      // Verify the invitation exists
      if ($companyInvite) {
        $invitation = CompanyMember::where('company_id', $companyInvite->id)
          ->where('email', $prefilledEmail)
          ->where('status', 'invited')
          ->whereNull('user_id')
          ->first();
          
        if (!$invitation) {
          // Invalid invitation
          $companyInvite = null;
          $prefilledEmail = null;
        }
      } else {
        // Company not found
        $companyInvite = null;
        $prefilledEmail = null;
      }
    }
    
    return view('auth.register', [
      'companyInvite' => $companyInvite,
      'prefilledEmail' => $prefilledEmail
    ]);
  }

  public function store(Request $request): RedirectResponse {
    $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
      'password' => ['required', 'confirmed', Rules\Password::defaults()],
      'telephone_number' => ['nullable','string'],
      'company_invite' => ['nullable', 'exists:companies,id']
    ]);

    $user = User::create([
      'name' => $request->name,
      'email' => strtolower($request->email),
      'password' => Hash::make($request->password),
      'telephone_number' => $request->telephone_number
    ]);

    // Handle company invitation if present
    if ($request->has('company_invite')) {
      $companyId = $request->company_invite;
      $invitation = CompanyMember::where('company_id', $companyId)
        ->where('email', strtolower($request->email))
        ->where('status', 'invited')
        ->whereNull('user_id')
        ->first();
        
      if ($invitation) {
        // Update the invitation to link to the new user and activate it
        $invitation->update([
          'user_id' => $user->id,
          'status' => 'active',
          'joined_at' => now()
        ]);
      }
    }

    event(new Registered($user));

    // Start 10-day free trial
    try {
        $user->newSubscription('basic', 'price_1QbtKfGVcskF822y3QlF13vZ')
            ->trialDays(10)
            ->create();
    } catch (\Exception $e) {
        // Log the error but don't fail registration
        \Log::warning('Failed to create trial subscription for user ' . $user->id . ': ' . $e->getMessage());
    }

    $message = 'Account successfully created with 10-day free trial! Please verify your email';
    if ($request->has('company_invite')) {
      $company = Company::find($request->company_invite);
      if ($company) {
        $message .= " and you'll automatically be added to {$company->name}";
      }
    }

    return redirect()->route('login')->with('alert', [
      'type' => 'success',
      'message' => $message
    ]);
  }
}
