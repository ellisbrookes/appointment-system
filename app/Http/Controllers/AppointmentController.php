<?php

namespace App\Http\Controllers;

use App\Mail\AppointmentConfirmation;
use App\Mail\AppointmentCancelled;
use App\Mail\AppointmentUpdated;
use App\Mail\GuestAppointmentConfirmation;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use App\Services\TimeslotService;

class AppointmentController extends Controller
{
  protected TimeslotService $timeslotService;

  public function __construct(TimeslotService $timeslotService)
  {
    $this->timeslotService = $timeslotService;
  }
  /**
   * Render the index page with a list of appointments.
   * @return View
   */
  public function index(Request $request)
  {
    $user = $request->user();
    
    // Get appointments that the user has access to:
    // 1. Their own personal appointments (no company_id)
    // 2. Appointments from companies they belong to
    $query = Appointment::accessibleByUser($user)->with(['user', 'company']);

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $appointments = $query->orderBy('date', 'desc')->orderBy('timeslot', 'desc')->get();

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

    // Send email notification
    try {
      Mail::to($request->user())->send(new AppointmentUpdated($appointment));
    } catch (\Exception $e) {
      // Log the error but don't fail the request
      \Log::error('Failed to send appointment update email: ' . $e->getMessage());
    }

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
    $userSettings = auth()->user()->settings ?? [];
    // Ensure userSettings is always an array
    if (!is_array($userSettings)) {
        $userSettings = [];
    }
    $timeslots = $this->timeslotService->generateTimeslots($userSettings);
    $firstTimeslot = $this->timeslotService->getFirstTimeslot($timeslots);

    return view('dashboard.appointments.create-step-two', compact(
      'appointment',
      'daysInMonth',
      'startDayOfWeek',
      'month',
      'year',
      'timeslots',
      'firstTimeslot'
    ))->with([
      'currentDate' => $today->toDateString()
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
      // Merge user ID with appointment data
      $appointmentData = array_merge($appointmentData, $validatedData);
      
      // Optionally set company_id based on the current user's company
      // This allows both personal and company appointments
      $user = $request->user();
      $companyId = $this->getUserCompanyId($user);
      if ($companyId) {
        $appointmentData['company_id'] = $companyId;
      }
      // If no company_id, the appointment will be a personal appointment
      
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

    // Send email notification
    try {
      Mail::to($request->user())->send(new AppointmentCancelled($appointment));
    } catch (\Exception $e) {
      // Log the error but don't fail the request
      \Log::error('Failed to send appointment cancellation email: ' . $e->getMessage());
    }

    $request->session()->forget('appointment');

    return redirect()
      ->route('dashboard.appointments.index')->with('alert', [
          'type' => 'success',
          'message' => 'Appointment successfully cancelled!'
      ]);
  }

  /**
   * Approve a pending appointment.
   * @param Request $request
   * @param Appointment $appointment
   * @return RedirectResponse
   */
  public function approve(Request $request, Appointment $appointment)
  {
    // Only allow approval of pending appointments
    if ($appointment->status !== 'pending') {
      return redirect()->route('dashboard.appointments.index')->with('alert', [
        'type' => 'error',
        'message' => 'Only pending appointments can be approved.'
      ]);
    }
    
    // Update the appointment status to 'open'
    $appointment->update(['status' => 'open']);
    
    // Send confirmation email
    try {
      if ($appointment->user) {
        // Send to registered user
        Mail::to($appointment->user)->send(new AppointmentConfirmation($appointment));
      } elseif ($appointment->customer_email) {
        // Send to guest customer
        Mail::to($appointment->customer_email)->send(new GuestAppointmentConfirmation($appointment));
      }
    } catch (\Exception $e) {
      // Log the error but don't fail the request
      \Log::error('Failed to send appointment approval email: ' . $e->getMessage());
    }
    
    return redirect()->route('dashboard.appointments.index')->with('alert', [
      'type' => 'success',
      'message' => 'Appointment approved successfully!'
    ]);
  }

  /**
   * Get the company ID for the given user.
   * Returns the user's owned company or first active company they're a member of.
   */
  private function getUserCompanyId(User $user): ?int
  {
    // First try to get their owned company
    $company = $user->company;
    
    // If they don't own a company, get the first active company they're a member of
    if (!$company) {
      $activeCompanies = $user->activeCompanies;
      if ($activeCompanies->isNotEmpty()) {
        $company = $activeCompanies->first();
      }
    }
    
    return $company ? $company->id : null;
  }
}
