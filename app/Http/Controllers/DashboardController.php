<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Check if user needs to complete onboarding
        if (!isset($user->settings['onboarding_completed']) || $user->settings['onboarding_completed'] !== true) {
            return redirect()->route('onboarding.welcome');
        }

        $appointmentsCount = Appointment::where('user_id', $user->id)
            ->where('status', 'open')
            ->count();

        $cancelledCount = Appointment::where('user_id', $user->id)
            ->where('status', 'cancelled')
            ->count();

        $closedCount = Appointment::where('user_id', $user->id)
            ->where('status', 'closed')
            ->count();

        $recentAppointments = Appointment::where('user_id', $user->id)
            ->whereIn('status', ['open', 'cancelled'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact('appointmentsCount', 'cancelledCount', 'closedCount', 'recentAppointments'));
    }
}
