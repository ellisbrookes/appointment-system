<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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
            'email' => 'required|email|exists:users,email',
            'role' => 'required|in:admin,member'
        ]);
        
        $user = User::where('email', $validated['email'])->first();
        
        // Check if user is already a member
        if ($company->isMember($user->id) || $company->hasPendingInvite($user->id)) {
            return back()->withErrors(['email' => 'User is already a member or has a pending invitation.']);
        }
        
        // Create invitation
        CompanyMember::create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'role' => $validated['role'],
            'status' => 'invited',
        ]);
        
        // TODO: Send invitation email
        
        return back()->with('alert', [
            'type' => 'success',
            'message' => 'Invitation sent successfully.'
        ]);
    }

    public function acceptInvite(Company $company): RedirectResponse
    {
        $user = auth()->user();
        
        $membership = CompanyMember::where('company_id', $company->id)
            ->where('user_id', $user->id)
            ->where('status', 'invited')
            ->firstOrFail();
            
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
}
