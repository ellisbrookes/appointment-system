<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    // ✅ Show all companies (view page)
    public function index()
    {
        $companies = Company::where('user_id', auth()->id())->get(); // Optional: filter by logged-in user
        return view('dashboard.company.index', compact('companies'));
    }

    // Show the form to create a new company
    public function create(Request $request)
    {
        $companyName = auth()->user()->company_name ?? null;
        return view('dashboard.company.create', compact('companyName'));
    }

    // Handle the form submission
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postcode' => 'nullable|string|max:20',
        ]);

        $company = Company::create([
            'user_id' => auth()->id(),
            ...$validated,
        ]);

        return redirect()->route('dashboard.company.edit', $company)->with('success', 'Company created successfully.');
    }

    // Show the edit form
    public function edit(Company $company)
    {
        return view('dashboard.company.edit', compact('company'));
    }

    // Handle the update
    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postcode' => 'nullable|string|max:20',
        ]);

        $company->update($validated);

        return redirect()->route('dashboard.company.edit', $company)->with('success', 'Company updated successfully.');
    }
}
