<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyMember;
use App\Models\User;
use App\Mail\CompanyInvitation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;

class CompanyMemberController extends Controller
{
    public function index(Company $company): View
    {
        $this->authorizeCompanyAdmin($company);
        
        $members = $company->members()
            ->with('user')
            ->orderBy('role')
            ->orderBy('joined_at')
            ->get();
            
        return view('dashboard.company.members.index', compact('company', 'members'));
    }

    public function invite(Request $request, Company $company): RedirectResponse
    {
        $this->authorizeCompanyAdmin($company);
        
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'role' => 'required|in:admin,member',
        ]);
        
        $user = User::where('email', $validated['email'])->first();
        
        // If user exists, check if they're already a member
        if ($user) {
            if ($company->isMember($user->id) || $company->hasPendingInvite($user->id)) {
                return back()->withErrors(['email' => 'User is already a member or has a pending invitation.']);
            }
        }
        
        // Check if there's already an invitation for this email (for non-existing users)
        $existingInvitation = CompanyMember::where('company_id', $company->id)
            ->whereNull('user_id')
            ->where('email', $validated['email'])
            ->where('status', 'invited')
            ->first();
            
        if ($existingInvitation) {
            return back()->withErrors(['email' => 'An invitation has already been sent to this email address.']);
        }
        
        // Create invitation
        $invitation = CompanyMember::create([
            'company_id' => $company->id,
            'user_id' => $user ? $user->id : null,
            'email' => $validated['email'], // Store email for non-existing users
            'role' => $validated['role'],
            'status' => 'invited',
        ]);
        
        // Send invitation email
        try {
            Mail::to($validated['email'])->send(new CompanyInvitation($invitation, $company));
        } catch (\Exception $e) {
            // Log the error but don't fail the invitation creation
            \Log::error('Failed to send company invitation email', [
                'email' => $validated['email'],
                'company_id' => $company->id,
                'error' => $e->getMessage()
            ]);
        }
        
        return back()->with('alert', [
            'type' => 'success',
            'message' => 'Invitation sent successfully.'
        ]);
    }

    public function showAcceptInvite(Company $company): View|RedirectResponse
    {
        $user = auth()->user();
        
        // If user is not authenticated, redirect to login with intention to return here
        if (!$user) {
            return redirect()->route('login')->with('alert', [
                'type' => 'info',
                'message' => 'Please log in to accept your invitation to join ' . $company->name
            ]);
        }
        
        // Check if user has a pending invitation
        $membership = CompanyMember::where('company_id', $company->id)
            ->where('user_id', $user->id)
            ->where('status', 'invited')
            ->first();
            
        if (!$membership) {
            return redirect()->route('dashboard.companies.index')
                ->with('alert', [
                    'type' => 'error',
                    'message' => 'No pending invitation found for this company.'
                ]);
        }
        
        return view('dashboard.company.members.accept', compact('company', 'membership'));
    }
    
    public function acceptInvite(Company $company): RedirectResponse
    {
        // Add debugging
        \Log::info('acceptInvite method called', [
            'company_id' => $company->id,
            'user_id' => auth()->id(),
            'request_method' => request()->method(),
            'request_url' => request()->url()
        ]);
        
        $user = auth()->user();
        
        if (!$user) {
            \Log::warning('User not authenticated in acceptInvite');
            return redirect()->route('login')
                ->with('alert', [
                    'type' => 'error',
                    'message' => 'You must be logged in to accept an invitation.'
                ]);
        }
        
        $membership = CompanyMember::where('company_id', $company->id)
            ->where('user_id', $user->id)
            ->where('status', 'invited')
            ->first();
            
        if (!$membership) {
            return redirect()->route('dashboard.companies.index')
                ->with('alert', [
                    'type' => 'error',
                    'message' => 'No pending invitation found for this company.'
                ]);
        }
            
        $membership->update([
            'status' => 'active',
            'joined_at' => now()
        ]);
        
        return redirect()->route('dashboard.companies.show', $company)
            ->with('alert', [
                'type' => 'success',
                'message' => 'Successfully joined the company!'
            ]);
    }

    public function updateRole(Request $request, Company $company, CompanyMember $member): RedirectResponse
    {
        $this->authorizeCompanyAdmin($company);
        
        // Prevent changing owner role
        if ($member->role === 'owner') {
            return back()->withErrors(['role' => 'Cannot change owner role.']);
        }
        
        $validated = $request->validate([
            'role' => 'required|in:admin,member'
        ]);
        
        $member->update(['role' => $validated['role']]);
        
        return back()->with('alert', [
            'type' => 'success',
            'message' => 'Member role updated successfully.'
        ]);
    }

    public function remove(Company $company, CompanyMember $member): RedirectResponse
    {
        $this->authorizeCompanyAdmin($company);
        
        // Prevent removing owner
        if ($member->role === 'owner') {
            return back()->withErrors(['error' => 'Cannot remove company owner.']);
        }
        
        $member->delete();
        
        return back()->with('alert', [
            'type' => 'success',
            'message' => 'Member removed successfully.'
        ]);
    }

    public function leave(Company $company): RedirectResponse
    {
        $user = auth()->user();
        
        $membership = CompanyMember::where('company_id', $company->id)
            ->where('user_id', $user->id)
            ->firstOrFail();
            
        // Prevent owner from leaving
        if ($membership->role === 'owner') {
            return back()->withErrors(['error' => 'Company owner cannot leave. Please transfer ownership first.']);
        }
        
        $membership->delete();
        
        return redirect()->route('dashboard.companies.index')
            ->with('alert', [
                'type' => 'success',
                'message' => 'Successfully left the company.'
            ]);
    }

    private function authorizeCompanyAdmin(Company $company)
    {
        $user = auth()->user();
        
        if (!$user->isAdminOf($company->id)) {
            abort(403, 'Unauthorized action. Admin access required.');
        }
    }

    // Current user's company member management methods
  
  public function currentUserCompanyMembers()
  {
    $user = auth()->user();
    
    // Get the user's owned company first
    $company = $user->company;
    
    // If they don't own a company, check if they're a member of any active companies with admin access
    if (!$company) {
      $adminCompanies = $user->activeCompanies()->wherePivotIn('role', ['admin', 'owner'])->get();
      
      if ($adminCompanies->isEmpty()) {
        return redirect()->route('dashboard.companies.create')
          ->with('alert', [
            'type' => 'error',
            'message' => 'You need to create a company or have admin access to manage team members.'
          ]);
      }
      
      // Use the first company they have admin access to
      $company = $adminCompanies->first();
    }
    
    // Double-check user has admin access to this company
    if (!$user->isAdminOf($company->id)) {
      abort(403, 'Unauthorized. You need admin access to manage team members.');
    }
    
    $members = $company->members()->with('user')->orderBy('role')->orderBy('created_at')->get();
    
    return view('dashboard.company.members.index', compact('members', 'company'));
  }
  
  public function currentUserCompanyInvite(Request $request)
  {
    $user = auth()->user();
    $company = $user->company;
    
    // If they don't own a company, check if they're an admin of any active companies
    if (!$company) {
      $adminCompanies = $user->activeCompanies()->wherePivotIn('role', ['admin', 'owner'])->get();
      
      if ($adminCompanies->isEmpty()) {
        return redirect()->route('dashboard.companies.create')
          ->with('alert', [
            'type' => 'error',
            'message' => 'You need admin access to invite members.'
          ]);
      }
      
      $company = $adminCompanies->first();
    }
    
    return $this->invite($request, $company);
  }
  
  public function currentUserCompanyUpdateRole(Request $request, CompanyMember $member)
  {
    $user = auth()->user();
    
    // Get the company from the member being updated
    $company = $member->company;
    
    // Check if the user has admin access to this specific company
    if (!$user->isAdminOf($company->id)) {
      abort(403, 'Unauthorized. You need admin access to manage members in this company.');
    }
    
    return $this->updateRole($request, $company, $member);
  }
  
  public function currentUserCompanyRemove(CompanyMember $member)
  {
    $user = auth()->user();
    
    // Get the company from the member being removed
    $company = $member->company;
    
    // Check if the user has admin access to this specific company
    if (!$user->isAdminOf($company->id)) {
      abort(403, 'Unauthorized. You need admin access to remove members from this company.');
    }
    
    return $this->remove($company, $member);
  }
}
