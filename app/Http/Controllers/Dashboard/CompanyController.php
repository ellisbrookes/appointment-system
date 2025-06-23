<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


class CompanyController extends Controller
{
  public function index(): View
  {
    // Get companies where user is owner or active member
    $companies = auth()->user()->activeCompanies()->with(['user', 'members.user'])->get();
    return view('dashboard.company.index', compact('companies'));
  }

  public function create()
  {
    return view('dashboard.company.create');
  }

  public function store(Request $request): RedirectResponse
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'nullable|email|max:255',
      'phone_number' => [
          'nullable',
          'regex:/^(?:0|\+?44)(?:\d\s?){9,10}$/i'
      ],
      'address' => 'nullable|max:255',
      'postcode' => [
          'nullable',
          'regex:/^([A-Z]{1,2}[0-9][0-9A-Z]? ?[0-9][A-Z]{2})$/i'
      ],
      'description' => 'nullable|string',
    ]);

    $company = Company::create([
      'name' => $validated['name'],
      'email' => $validated['email'],
      'phone_number' => $validated['phone_number'],
      'address' => $validated['address'],
      'postcode' => $validated['postcode'],
      'description' => $validated['description'] ?? null,
      'user_id' => auth()->id(),
    ]);

    // Create owner membership record
    $company->members()->create([
      'user_id' => auth()->id(),
      'role' => 'owner',
      'status' => 'active',
      'joined_at' => now(),
    ]);

    return redirect()->route('dashboard.companies.index')->with('alert', [
      'type' => 'success',
      'message' => 'Company created successfully.',
    ]);
  }

  public function show(Company $company)
  {
    $this->authorizeCompany($company);
    return view('dashboard.company.show', compact('company'));
  }

  public function edit(Company $company)
  {
    $this->authorizeCompanyAdmin($company);
    return view('dashboard.company.edit', compact('company'));
  }

  public function update(Request $request, Company $company)
  {
    $this->authorizeCompanyAdmin($company);

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'nullable|email|max:255',
      'phone_number' => [
          'nullable',
          'regex:/^(\+44\s?7\d{3}|\(?07\d{3}\)?)\s?\d{3}\s?\d{3}$/'
      ],
      'address' => 'nullable|max:255',
      'postcode' => [
          'nullable',
          'regex:/^([A-Z]{1,2}[0-9][0-9A-Z]? ?[0-9][A-Z]{2})$/i'
      ],
      'description' => 'nullable|string',
    ]);

    $company->update($validated);

    return redirect()->route('dashboard.companies.index')->with('alert', [
      'type' => 'success',
      'message' => 'Company updated successfully.',
    ]);
  }

  // Delete a company
  public function destroy(Company $company)
  {
    $this->authorizeCompanyAdmin($company);
    $company->delete();

    return redirect()->route('dashboard.companies.index')->with('alert', [
      'type' => 'success',
      'message' => 'Company deleted successfully.',
    ]);
  }

  private function authorizeCompany(Company $company)
  {
    $user = auth()->user();
    
    // Check if user is a member of this company
    if (!$user->isMemberOf($company->id)) {
      abort(403, 'Unauthorized action.');
    }
  }

  private function authorizeCompanyAdmin(Company $company)
  {
    $user = auth()->user();
    
    // Check if user is an admin or owner of this company
    if (!$user->isAdminOf($company->id)) {
      abort(403, 'Unauthorized action. Admin access required.');
    }
  }
};
