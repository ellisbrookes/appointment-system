<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Count how many 'open' appointments the user has
        $appointmentsCount = Appointment::where('user_id', $user->id)
            ->where('status', 'open')
            ->count();

        // Get the 5 most recent 'open' appointments
        $recentAppointments = Appointment::where('user_id', $user->id)
            ->where('status', 'open')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.index', compact('appointmentsCount', 'recentAppointments'));
    }
}
