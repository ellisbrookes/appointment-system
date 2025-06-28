<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Carbon\Carbon;

class CompanyCalendarController extends Controller
{
    /**
     * Display the company calendar view.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = auth()->user();
        $company = $this->getUserCompany($user);
        
        if (!$company) {
            return redirect()->route('dashboard.companies.create')
                ->with('alert', [
                    'type' => 'error',
                    'message' => 'You need to create a company or be invited to one first.'
                ]);
        }

        // Check if user has access to view company calendar
        if (!$user->isMemberOf($company->id)) {
            abort(403, 'Unauthorized. You must be a member of this company to view the calendar.');
        }

        // Get calendar month/year from request or use current
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);
        $currentDate = Carbon::create($year, $month, 1);
        
        // Get appointments for the company for the selected month
        $appointments = Appointment::forCompany($company->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->with(['user'])
            ->orderBy('date')
            ->orderBy('timeslot')
            ->get();

        // Group appointments by date for easier calendar rendering
        $appointmentsByDate = $appointments->groupBy(function($appointment) {
            return Carbon::parse($appointment->date)->format('Y-m-d');
        });

        // Generate calendar data
        $calendarData = $this->generateCalendarData($currentDate, $appointmentsByDate);

        return view('dashboard.company.calendar.index', compact(
            'company', 
            'calendarData', 
            'currentDate', 
            'appointments',
            'appointmentsByDate'
        ));
    }

    /**
     * Show appointments for a specific date.
     */
    public function showDate(Request $request, string $date): View|RedirectResponse
    {
        $user = auth()->user();
        $company = $this->getUserCompany($user);
        
        if (!$company) {
            return redirect()->route('dashboard.companies.create')
                ->with('alert', [
                    'type' => 'error',
                    'message' => 'You need to create a company or be invited to one first.'
                ]);
        }

        if (!$user->isMemberOf($company->id)) {
            abort(403, 'Unauthorized. You must be a member of this company to view appointments.');
        }

        $selectedDate = Carbon::parse($date);
        
        $appointments = Appointment::forCompany($company->id)
            ->whereDate('date', $selectedDate)
            ->with(['user'])
            ->orderBy('timeslot')
            ->get();

        return view('dashboard.company.calendar.date', compact(
            'company', 
            'appointments', 
            'selectedDate'
        ));
    }

    /**
     * Generate calendar data for the given month.
     */
    private function generateCalendarData(Carbon $currentDate, $appointmentsByDate): array
    {
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();
        
        // Get the first day of the week for the month (start calendar on Monday)
        $startOfCalendar = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $endOfCalendar = $endOfMonth->copy()->endOfWeek(Carbon::SUNDAY);
        
        $weeks = [];
        $currentWeek = [];
        
        for ($date = $startOfCalendar->copy(); $date->lte($endOfCalendar); $date->addDay()) {
            $dateString = $date->format('Y-m-d');
            $appointmentsForDate = $appointmentsByDate->get($dateString, collect());
            
            $currentWeek[] = [
                'date' => $date->copy(),
                'isCurrentMonth' => $date->month === $currentDate->month,
                'isToday' => $date->isToday(),
                'appointments' => $appointmentsForDate,
                'appointmentCount' => $appointmentsForDate->count()
            ];
            
            // If it's Sunday, start a new week
            if ($date->isSunday()) {
                $weeks[] = $currentWeek;
                $currentWeek = [];
            }
        }
        
        // Add any remaining days
        if (!empty($currentWeek)) {
            $weeks[] = $currentWeek;
        }
        
        return $weeks;
    }

    /**
     * Get the user's company (owned or first active company).
     */
    private function getUserCompany(User $user): ?Company
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
        
        return $company;
    }
}
