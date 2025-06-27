<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyPublicController extends Controller
{
    /**
     * Show the public company booking flow.
     */
    public function show(string $companyUrl): View
    {
        $company = Company::where('url', $companyUrl)->firstOrFail();
        
        // Get available time slots (you can customize this based on company settings)
        $availableTimeSlots = [
            '09:00' => '9:00 AM',
            '10:00' => '10:00 AM', 
            '11:00' => '11:00 AM',
            '14:00' => '2:00 PM',
            '15:00' => '3:00 PM',
            '16:00' => '4:00 PM'
        ];
            
        return view('company.public.booking', compact('company', 'availableTimeSlots'));
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
            'service' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:500'
        ]);
        
        // Create the appointment with customer information
        $appointment = $company->appointments()->create([
            'date' => $validated['appointment_date'],
            'timeslot' => $validated['appointment_time'],
            'service' => $validated['service'] ?? 'General Appointment',
            'status' => 'pending', // Set as pending for company approval
            'user_id' => null, // No user account required for public bookings
            'customer_name' => $validated['name'],
            'customer_email' => $validated['email'],
            'customer_phone' => $validated['phone'],
            'customer_message' => $validated['message'] ?? null,
        ]);
        
        return redirect()->route('company.public', $companyUrl)
            ->with('success', 'Your appointment has been booked successfully! We will contact you soon to confirm.');
    }
}
