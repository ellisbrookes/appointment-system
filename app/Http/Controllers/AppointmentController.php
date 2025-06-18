<?php

namespace App\Http\Controllers;

use App\Mail\AppointmentConfirmation;
use App\Mail\AppointmentCancelled;
use App\Mail\AppointmentUpdated;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
  /**
   * Render the index page with a list of appointments.
   * @return View
   */
  public function index(Request $request)
  {
    $query = Appointment::with('user');

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $appointments = $query->get();

    return view('dashboard.appointments.index', compact('appointments'));
  }

  /**
    * Show the edit form for an existing appointment
    * @param Appointment $appointment
    * @return Response
  */
  public function edit(Appointment $appointment)
  {
    $users = User::all();
    return view('dashboard.appointments.edit', compact('appointment', 'users'));
  }

  /**
   * @param Request $request
   * @param Appointment $appointment
   * @return RedirectResponse
  */
  public function update(Request $request, Appointment $appointment)
  {
    $validatedData = $request->validate([
      'service' => 'required|string|max:255',
      'date' => 'required|string',
      'timeslot' => 'required|string',
      'user_id' => 'required|exists:users,id',
    ]);

    $appointment->update($validatedData);

    Mail::to($request->user())->send(new AppointmentUpdated($appointment));

    return redirect()->route('dashboard.appointments.index')->with('alert', [
      'type' => 'success',
      'message' => 'Appointment updated successfully'
    ]);
  }

  /**
   * Show step one of creating an appointment, selecting the service.
   * @return Response
   */
  public function createStepOne(Request $request)
  {
    $appointment = $request->session()->get('appointment', []);
    return view('dashboard.appointments.create-step-one', compact('appointment'));
  }

  /**
   * Post request to store the service in the session.
   * @param Request $request
   * @return RedirectResponse
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
   * @return Response
   */
  public function createStepTwo(Request $request)
  {
    $appointment = $request->session()->get('appointment', []);

    // Calendar Data
    $today = Carbon::today();
    
    $year = $request->input('year', $today->year);
    $month = $request->input('month', $today->month);

    $startOfMonth = Carbon::create($year, $month, 1);
    $endOfMonth = $startOfMonth->copy()->endOfMonth();
    
    $startDayOfWeek = $startOfMonth->dayOfWeek;
    $daysInMonth = $endOfMonth->day;

    // Timeslots
    $settings = auth()->user()->settings ?? [];

    $startTime = isset($settings['timeslot_start']) ? Carbon::createFromTimeString($settings['timeslot_start']) : Carbon::createFromTimeString('09:00');
    $endTime = isset($settings['timeslot_end']) ? Carbon::createFromTimeString($settings['timeslot_end']) : Carbon::createFromTimeString('17:00');
    $interval = isset($settings['timeslot_interval']) ? (int)$settings['timeslot_interval'] : 60;

    $timeslots = [];

    while ($startTime < $endTime) {
      $timeslots[] = $startTime->format('H:i');
      $startTime->addMinutes($interval);
    }

    $firstTimeslot = Carbon::parse($timeslots[0]);

    return view('dashboard.appointments.create-step-two', [
      'appointment',
      'currentDate' => $today->toDateString(),
      'daysInMonth' => $daysInMonth,
      'startDayOfWeek' => $startDayOfWeek,
      'month' => $month,
      'year' => $year,
      'timeslots' => $timeslots,
      'firstTimeslot' => $firstTimeslot
    ]);
  }

  /**
   * Post request to store the date and timeslot in the session.
   * @param Request $request
   * @return RedirectResponse
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
   * @return Response
   */
  public function createStepThree(Request $request)
  {
    $appointment = $request->session()->get('appointment', []);
    $users = User::all(); // Fetch all users to display in the dropdown
    
    return view('dashboard.appointments.create-step-three', compact('appointment', 'users'));
  }

  /**
   * Post request to create the appointment using session data.
   * @param Request $request
   * @return RedirectResponse
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
      $appointment = Appointment::create($appointmentData); // Create and retrieve the Appointment instance

      // Send an email confirmation
      Mail::to($request->user())->send(new AppointmentConfirmation($appointment)); // Pass the Appointment instance

      $request->session()->forget('appointment');
    }

    return redirect()
      ->route('dashboard.appointments.index')->with('alert', [
        'type' => 'success',
        'message' => 'Appointment created successfully!'
    ]);
  }

 public function destroy(Request $request, $id)
  {
    $appointment = Appointment::findOrFail($id);

    $appointment->status = 'cancelled';
    $appointment->save();

    Mail::to($request->user())->send(new AppointmentCancelled($appointment));

    $request->session()->forget('appointment');

    return redirect()
      ->route('dashboard.appointments.index')->with('alert', [
        'type' => 'success',
        'message' => 'Appointment successfully cancelled!'
    ]);
  }
}
