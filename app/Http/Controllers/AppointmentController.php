<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function index(Request $request)
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

    public function createStepOne(Request $request)
    {
        // Calendar Data
        $currentDate = Carbon::now();
        $daysInMonth = $currentDate->daysInMonth;
        $firstDayOfMonth = $currentDate->copy()->startOfMonth()->dayOfWeek;

        // Get the selected day from the request (if any)
        $selectedDay = $request->query('selected_day', $currentDate->day); 

        $appointment = $request->session()->get('appointment');

        return view('dashboard.appointments.create-step-one', compact('appointment', 'currentDate', 'daysInMonth', 'firstDayOfMonth', 'selectedDay'));
    }

    public function postCreateStepOne(Request $request)
    {
        // Validate the data
        $validatedData = $request->validate([
            'service' => 'required',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        // Get the session appointment or create a new one
        if (empty($request->session()->get('appointment'))) {
            $appointment = new Appointment();
            $appointment->fill($validatedData);  // Using the fill method to assign data
            $request->session()->put('appointment', $appointment);  // Store in session
        } else {
            $appointment = $request->session()->get('appointment');
            $appointment->fill($validatedData);  // Update appointment data
            $request->session()->put('appointment', $appointment);  // Store updated data in session
        }

        // Redirect to next step
        return redirect()->route('appointments.create.step.two');
    }
    
    public function createStepTwo(Request $request)
    {
        $appointment = $request->session()->get('appointment');
        return view('dashboard.appointments.create-step-two', compact('appointment'));
    }

    public function postCreateStepTwo(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
        ]);

        if (empty($request->session()->get('appointment'))) {
            $appointment = new Appointment();
            $appointment->fill($validatedData);
            $request->session()->put('appointment', $appointment);
        } else {
            $appointment = $request->session()->get('appointment');
            $appointment->fill($validatedData);
            $request->session()->put('appointment', $appointment);
        }

        return redirect()->route('appointments.create.step.three');
    }

    public function createStepThree(Request $request)
    {
        $appointment = $request->session()->get('appointment');
        return view('dashboard.appointments.create-step-three', compact('appointment'));
    }

    public function postCreateStepThree(Request $request)
    {
        $appointment = $request->session()->get('appointment');
        $appointment->save();

        return redirect()->route('appointments.index');
    }
}
?>