<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    /**
    * Render the index page with a list of appointments
    * @return \Illuminate\Contracts\View\View
    */
    public function index(Request $request)
    {
        $appointments = Appointment::all();
        return view('dashboard.appointments.index', compact('appointments'));
    }

    /**
    * Show step one of creating an appointment, selecting the service
    * @return \Illuminate\Http\Response
    */
    public function createStepOne(Request $request)
    {
        $appointment = $request->session()->get('appointment');
        return view('dashboard.appointments.create-step-one',compact('appointment'));
    }

    /**
    * Post request to store the service in the session
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\RedirectResponse
    */
    public function createPostStepOne(Request $request)
    {
        $validatedData = $request->validate([
            'service' => 'required',
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

        return redirect()->route('dashboard.appointments.create.step.two');
    }

    /**
    * Show step two of creating an appointment, date and time
    * @return \Illuminate\Http\Response
    */
    public function createStepTwo(Request $request)
    {
        $appointment = $request->session()->get('appointment');

        // Calendar Data
        $date = $request->get('date', now()->format('d-m-Y'));
        $currentDate = Carbon::parse($date);

        // Timeslots
        $timeslots = [
            '9:00 AM',
            '10:00 AM',
            '11:00 AM',
            '12:00 PM',
            '1:00 PM',
            '2:00 PM',
            '3:00 PM',
            '4:00 PM',
            '5:00 PM'
        ];

        // Selected date
        $selectedDay = $request->query('date', $currentDate->day);

        // First and last day of the month
        $firstDay = $currentDate->copy()->startOfMonth();
        $lastDay = $currentDate->copy()->endOfMonth();

        // Days for the calendar
        $daysInMonth = $lastDay->day;
        $startDayOfWeek = $firstDay->dayOfWeek;

        return view('dashboard.appointments.create-step-two', compact('appointment', 'currentDate', 'daysInMonth', 'startDayOfWeek', 'selectedDay', 'timeslots'));
    }

    /**
    * Post request to store the service, time and date in the session
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\RedirectResponse
    */
    public function createPostStepTwo(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required',
            'timeslot' => 'required',
        ]);

        $appointment = $request->session()->get('appointment');
        $appointment->fill($validatedData);
        $request->session()->put('appointment', $appointment);

        return redirect()->route('/dashboard/appointments/create-step-three');
    }

    /**
    * Show step two of creating an appointment, review
    * @return \Illuminate\Http\Response
    */
    public function createStepThree(Request $request)
    {
        $appointment = $request->session()->get('appointment');
        return view('appointments.create-step-three',compact('appointment'));
    }

    /**
    * Post request to create the appoointment using the session data
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\RedirectResponse
    */
    public function createPostStepThree(Request $request)
    {
        $appointment = $request->session()->get('appointment');
        $appointment->save();

        $request->session()->forget('appointment');

        return redirect()->route('/dashboard/appointments');
    }
}
?>
