<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $userCount = Cache::remember('userCount', 60, fn() => User::count());
        $appointmentCount = Cache::remember('appointmentCount', 60, fn() => Appointment::count());

        return view('index', [
            'userCount' => $userCount,
            'appointmentCount' => $appointmentCount,
        ]);
    }
}
