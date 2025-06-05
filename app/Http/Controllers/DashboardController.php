<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $appointmentsCount = $user->appointments()->count();

        return view('dashboard.index', compact('appointmentsCount'));
    }
}
