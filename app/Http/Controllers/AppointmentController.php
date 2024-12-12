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
}
?>
