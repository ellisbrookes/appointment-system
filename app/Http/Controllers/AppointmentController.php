<?php

namespace App\Http\Controllers;

use App\Mail\AppointmentConfirmation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\User;


class AppointmentController extends Controller
{
    /**
     * Render the index page with a list of appointments.
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $appointments = Appointment::with('user')->get();
        return view('dashboard.appointments.index', compact('appointments'));
    }

    /**
     * Show step one of creating an appointment, selecting the service.
     * @return \Illuminate\Http\Response
     */
    public function createStepOne(Request $request)
    {
        $appointment = $request->session()->get('appointment', []);
        return view('dashboard.appointments.create-step-one', compact('appointment'));
    }

    /**
     * Post request to store the service in the session.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createPostStepOne(Request $request)
    {
        $validatedData = $request->validate([
            'service' => 'required',
        ]);

        // Store the data as an array in the session
        $appointment = $request->session()->get('appointment', []);
        $appointment = array_merge($appointment, $validatedData);
        $request->session()->put('appointment', $appointment);

        return redirect()->route('dashboard.appointments.create.step.two');
    }

    /**
     * Show step two of creating an appointment, selecting date and time.
     * @return \Illuminate\Http\Response
     */
    public function createStepTwo(Request $request)
    {
        $appointment = $request->session()->get('appointment', []);

        // Calendar Data
        $date = $request->get('date', now()->format('Y-m-d'));
        $currentDate = Carbon::parse($date);

        // Timeslots
        $timeslots = [
            '9:00',
            '10:00',
            '11:00',
            '12:00',
            '1:00',
            '2:00',
            '3:00',
            '4:00',
            '5:00'
        ];

        $firstTimeslot = Carbon::parse($timeslots[0]);

        // Selected date
        $selectedDay = $request->query('date', $currentDate->day);

        // First and last day of the month
        $firstDay = $currentDate->copy()->startOfMonth();
        $lastDay = $currentDate->copy()->endOfMonth();

        // Days for the calendar
        $daysInMonth = $lastDay->day;
        $startDayOfWeek = $firstDay->dayOfWeek;

        return view('dashboard.appointments.create-step-two', compact(
            'appointment',
            'currentDate',
            'daysInMonth',
            'startDayOfWeek',
            'selectedDay',
            'timeslots',
            'firstTimeslot'
        ));
    }

    /**
     * Post request to store the date and timeslot in the session.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createPostStepTwo(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'timeslot' => 'required',
        ]);

        // Store the data in the session
        $appointment = $request->session()->get('appointment', []);
        $appointment = array_merge($appointment, $validatedData);
        $request->session()->put('appointment', $appointment);

        return redirect()->route('dashboard.appointments.create.step.three');
    }

    /**
     * Show step three of creating an appointment, reviewing details.
     * This step now includes a dropdown for selecting a user.
     * @return \Illuminate\Http\Response
     */
    public function createStepThree(Request $request)
    {
        $appointment = $request->session()->get('appointment', []);
        $users = User::all(); // Fetch all users to display in the dropdown
        return view('dashboard.appointments.create-step-three', compact('appointment', 'users'));
    }

    /**
     * Post request to create the appointment using session data.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createPostStepThree(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id', // Ensure a valid user is selected
        ]);

        $appointmentData = $request->session()->get('appointment');

        if ($appointmentData) {
            // Merge user ID with appointment data and save it to the database
            $appointmentData = array_merge($appointmentData, $validatedData);
            Appointment::create($appointmentData);

            // Send an email confirmation
            Mail::to($appointment->email)->send(new AppointmentConfirmation($appointmentData));

            $request->session()->forget('appointment');

        }

        return redirect()->route('dashboard.appointments.index')->with('success', 'Appointment created successfully!');
    }
}
