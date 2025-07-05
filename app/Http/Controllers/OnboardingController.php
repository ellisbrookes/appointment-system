<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\CompanyMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class OnboardingController extends Controller
{

    /**
     * Show the onboarding welcome screen
     */
    public function welcome(): View
    {
        $user = Auth::user();
        
        // Check if user has already completed onboarding
        if ($this->hasCompletedOnboarding($user)) {
            return redirect()->route('dashboard');
        }

        return view('onboarding.welcome', compact('user'));
    }

    /**
     * Show the account type selection step
     */
    public function accountType(): View
    {
        $user = Auth::user();
        
        if ($this->hasCompletedOnboarding($user)) {
            return redirect()->route('dashboard');
        }

        return view('onboarding.account-type', compact('user'));
    }

    /**
     * Process account type selection
     */
    public function storeAccountType(Request $request): RedirectResponse
    {
        $request->validate([
            'account_type' => ['required', 'in:individual,company']
        ]);

        $user = Auth::user();
        $accountType = $request->input('account_type');

        // Store account type in session for the onboarding flow
        session(['onboarding.account_type' => $accountType]);

        if ($accountType === 'individual') {
            return redirect()->route('onboarding.individual.setup');
        } else {
            return redirect()->route('onboarding.company.setup');
        }
    }

    /**
     * Show individual account setup
     */
    public function individualSetup(): View
    {
        $user = Auth::user();
        
        if ($this->hasCompletedOnboarding($user)) {
            return redirect()->route('dashboard');
        }

        if (session('onboarding.account_type') !== 'individual') {
            return redirect()->route('onboarding.account-type');
        }

        return view('onboarding.individual.setup', compact('user'));
    }

    /**
     * Process individual account setup
     */
    public function storeIndividual(Request $request): RedirectResponse
    {
        $request->validate([
            'business_name' => ['nullable', 'string', 'max:255'],
            'business_type' => ['nullable', 'string', 'max:255'],
            'working_hours_start' => ['required', 'date_format:H:i'],
            'working_hours_end' => ['required', 'date_format:H:i', 'after:working_hours_start'],
            'appointment_duration' => ['required', 'integer', 'min:15', 'max:480'],
            'services' => ['nullable', 'array'],
            'services.*' => ['string', 'max:255']
        ]);

        $user = Auth::user();
        
        // Update user settings
        $settings = $user->settings ?? [];
        $settings = array_merge($settings, [
            'business_name' => $request->input('business_name'),
            'business_type' => $request->input('business_type'),
            'timeslot_start' => $request->input('working_hours_start'),
            'timeslot_end' => $request->input('working_hours_end'),
            'timeslot_interval' => $request->input('appointment_duration'),
            'services' => array_filter($request->input('services', [])),
            'onboarding_completed' => true,
            'onboarding_completed_at' => now()->toDateTimeString()
        ]);

        $user->update(['settings' => $settings]);

        // Ensure user has a trial subscription
        $this->ensureTrialSubscription($user);

        return redirect()->route('onboarding.complete');
    }

    /**
     * Show company setup
     */
    public function companySetup(): View
    {
        $user = Auth::user();
        
        if ($this->hasCompletedOnboarding($user)) {
            return redirect()->route('dashboard');
        }

        if (session('onboarding.account_type') !== 'company') {
            return redirect()->route('onboarding.account-type');
        }

        return view('onboarding.company.setup', compact('user'));
    }

    /**
     * Process company setup
     */
    public function storeCompany(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9-]+$/', 'unique:companies,url'],
            'description' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'zip' => ['nullable', 'string', 'max:10'],
            'working_hours_start' => ['required', 'date_format:H:i'],
            'working_hours_end' => ['required', 'date_format:H:i', 'after:working_hours_start'],
            'appointment_duration' => ['required', 'integer', 'min:15', 'max:480'],
        ]);

        $user = Auth::user();

        // Create the company
        $company = Company::create([
            'name' => $request->input('name'),
            'url' => $request->input('url'),
            'description' => $request->input('description'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'zip' => $request->input('zip'),
            'user_id' => $user->id
        ]);

        // Add user as owner of the company
        CompanyMember::create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'role' => 'owner',
            'status' => 'active',
            'joined_at' => now()
        ]);

        // Update user settings
        $settings = $user->settings ?? [];
        $settings = array_merge($settings, [
            'timeslot_start' => $request->input('working_hours_start'),
            'timeslot_end' => $request->input('working_hours_end'),
            'timeslot_interval' => $request->input('appointment_duration'),
            'onboarding_completed' => true,
            'onboarding_completed_at' => now()->toDateTimeString()
        ]);

        $user->update(['settings' => $settings]);

        // Store company ID for team setup
        session(['onboarding.company_id' => $company->id]);

        return redirect()->route('onboarding.company.team');
    }

    /**
     * Show company team setup
     */
    public function companyTeam(): View
    {
        $user = Auth::user();
        $companyId = session('onboarding.company_id');
        
        if (!$companyId) {
            return redirect()->route('onboarding.account-type');
        }

        $company = Company::findOrFail($companyId);

        return view('onboarding.company.team', compact('user', 'company'));
    }

    /**
     * Process company team setup
     */
    public function storeCompanyTeam(Request $request): RedirectResponse
    {
        $request->validate([
            'team_members' => ['nullable', 'array'],
            'team_members.*.email' => ['required_with:team_members.*', 'email', 'max:255'],
            'team_members.*.role' => ['required_with:team_members.*', 'in:admin,member']
        ]);

        $companyId = session('onboarding.company_id');
        if (!$companyId) {
            return redirect()->route('onboarding.account-type');
        }

        $company = Company::findOrFail($companyId);
        $teamMembers = $request->input('team_members', []);

        foreach ($teamMembers as $member) {
            if (!empty($member['email'])) {
                // Check if user already exists
                $existingUser = User::where('email', strtolower($member['email']))->first();
                
                CompanyMember::create([
                    'company_id' => $company->id,
                    'user_id' => $existingUser?->id,
                    'email' => strtolower($member['email']),
                    'role' => $member['role'],
                    'status' => $existingUser ? 'active' : 'invited',
                    'joined_at' => $existingUser ? now() : null
                ]);

                // TODO: Send invitation email if user doesn't exist
            }
        }

        // Ensure user has a trial subscription
        $this->ensureTrialSubscription(Auth::user());

        return redirect()->route('onboarding.complete');
    }

    /**
     * Show onboarding completion
     */
    public function complete(): View
    {
        $user = Auth::user();
        $accountType = session('onboarding.account_type');
        $company = null;

        if ($accountType === 'company') {
            $companyId = session('onboarding.company_id');
            if ($companyId) {
                $company = Company::find($companyId);
            }
        }

        // Clear onboarding session data
        session()->forget(['onboarding.account_type', 'onboarding.company_id']);

        return view('onboarding.complete', compact('user', 'accountType', 'company'));
    }

    /**
     * Skip onboarding (mark as completed)
     */
    public function skip(): RedirectResponse
    {
        $user = Auth::user();
        
        $settings = $user->settings ?? [];
        $settings['onboarding_completed'] = true;
        $settings['onboarding_completed_at'] = now()->toDateTimeString();
        $settings['onboarding_skipped'] = true;

        $user->update(['settings' => $settings]);

        // Clear onboarding session data
        session()->forget(['onboarding.account_type', 'onboarding.company_id']);

        return redirect()->route('dashboard')->with('alert', [
            'type' => 'info',
            'message' => 'Onboarding skipped. You can complete your setup anytime from your settings.'
        ]);
    }

    /**
     * Check if user has completed onboarding
     */
    private function hasCompletedOnboarding(User $user): bool
    {
        return isset($user->settings['onboarding_completed']) && $user->settings['onboarding_completed'] === true;
    }

    /**
     * Generate a unique company URL
     */
    private function generateCompanyUrl(string $companyName): string
    {
        $baseUrl = Str::slug($companyName);
        $url = $baseUrl;
        $counter = 1;

        while (Company::where('url', $url)->exists()) {
            $url = $baseUrl . '-' . $counter;
            $counter++;
        }

        return $url;
    }

    /**
     * Ensure user has a trial subscription
     */
    private function ensureTrialSubscription(User $user): void
    {
        // Check if user already has a subscription
        if (!$user->subscription('basic')) {
            try {
                $user->newSubscription('basic', 'price_1QbtKfGVcskF822y3QlF13vZ')
                    ->trialDays(10)
                    ->create();
            } catch (\Exception $e) {
                // Log the error but don't fail the process
                \Log::warning('Failed to create trial subscription for user ' . $user->id . ': ' . $e->getMessage());
            }
        }
    }
}
