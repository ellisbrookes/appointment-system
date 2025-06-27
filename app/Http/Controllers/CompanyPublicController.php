<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyPublicController extends Controller
{
    /**
     * Show the public company calendar page.
     */
    public function show(string $companyUrl): View
    {
        $company = Company::where('url', $companyUrl)->firstOrFail();
        
        // Get upcoming appointments for this company (for availability display)
        $upcomingAppointments = $company->appointments()
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->take(10)
            ->get();
            
        return view('company.public.calendar', compact('company', 'upcomingAppointments'));
    }
    
    /**
     * Show the public booking form for a company.
     */
    public function bookingForm(string $companyUrl): View
    {
        $company = Company::where('url', $companyUrl)->firstOrFail();
        
        return view('company.public.booking', compact('company'));
    }
    
    /**
     * Process a public booking request.
     */
    public function processBooking(Request $request, string $companyUrl)
    {
        $company = Company::where('url', $companyUrl)->firstOrFail();
        
        // Validate booking request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
            'message' => 'nullable|string|max:500'
        ]);
        
        // Here you would create the appointment or booking request
        // For now, just redirect with success message
        
        return redirect()->route('company.public', $companyUrl)
            ->with('success', 'Your booking request has been submitted successfully!');
    }
}
