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
    $companies = Company::with('user')->get();
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
          'regex:/^(\+44\s?7\d{3}|\(?07\d{3}\)?)\s?\d{3}\s?\d{3}$/'
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

    return redirect()->route('dashboard.companies.index')->with('alert', [
      'type' => 'success',
      'message' => 'Company created successfully.',
    ]);
  }

  public function show(Company $company)
  {
    $this->authorizeCompany($company);
    return view('companies.show', compact('company'));
  }

  public function edit(Company $company)
  {
  }

  public function update(Request $request, Company $company)
  {
    $this->authorizeCompany($company);

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

    return redirect()->route('dashboard.companies.index')->with('success', 'Company updated successfully.');
  }

// Delete a company
  public function destroy(Company $company)
  {
    $this->authorizeCompany($company);
    $company->delete();

    return redirect()->route('dashboard.companies.index')->with('success', 'Company deleted successfully.');
  }
};
