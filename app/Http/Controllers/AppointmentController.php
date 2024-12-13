<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function showAppointments(Request $request)
    {
        // Get the current month and year
        $currentDate = Carbon::now();
        $daysInMonth = $currentDate->daysInMonth;
        $firstDayOfMonth = $currentDate->copy()->startOfMonth()->dayOfWeek;

        // Get the selected day from the request (if any)
        $selectedDay = $request->query('selected_day', $currentDate->day); // Default to today's day if no day is selected

        // Get the full date for the selected day
        $fullDate = Carbon::create($currentDate->year, $currentDate->month, $selectedDay)->format('l, F j, Y');

        // Pass the data to the view
        return view('dashboard.appointments.index', compact('currentDate', 'daysInMonth', 'firstDayOfMonth', 'selectedDay', 'fullDate'));
    }

    public function create(Request $request)
    {
        $step = $request->input('step', 1); // Default to step 1
        return view('dashboard.appointments.create', compact('step'));
    }

   public function store(Request $request)
{
    $step = $request->input('step', 1); // Default to step 1
    $data = $request->all();

    // Validate and store data based on the current step
    switch ($step) {
        case 1:
            // Validate service selection
            $request->validate(['service' => 'required']);
            $request->session()->put('appointment.service', $data['service']);
            break;

        case 2:
            // Validate date selection
            $request->validate(['selected_day' => 'required']);
            $request->session()->put('appointment.selected_day', $data['selected_day']);
            break;

        case 3:
            // Validate user details
            $request->validate([
                'user_name' => 'required',
            ]);
            $request->session()->put('appointment.user_name', $data['user_name']);
            break;

        case 4:
            // Final validation
            $request->validate([
                'service' => 'required',
                'selected_day' => 'required',
                'user_name' => 'required',
            ]);
            // Store the final details or process the appointment
            break;
    }

    // Redirect to the next step
    if ($step < 4) {
        return redirect()->route('appointments.create', ['step' => $step + 1]);
    }

    // If all steps are completed, redirect to the review page
    return redirect()->route('appointments.review');
    }
}
