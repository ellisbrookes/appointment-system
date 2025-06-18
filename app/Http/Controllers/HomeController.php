<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Support\Facades\Cache;
use App\Services\PricingService;

class HomeController extends Controller
{
    public function index(PricingService $pricingService)
    {
        $userCount = Cache::remember('userCount', 60, fn() => User::count());
        $appointmentCount = Cache::remember('appointmentCount', 60, fn() => Appointment::count());

        $productsWithPrices = Cache::remember('productsWithPrices', 60, function () use ($pricingService) {
            return $pricingService->getProductsWithPrices();
        });

        return view('index', compact('productsWithPrices', 'userCount', 'appointmentCount'));
    }
}
