<?php

namespace App\Http\Controllers;

use App\Services\PricingService;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index(PricingService $pricingService)
    {
        try {
            $productsWithPrices = $pricingService->getProductsWithPrices();
            return view('pricing.index', compact('productsWithPrices'));
        } catch (\RuntimeException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function selectPlan(Request $request)
    {
        $planId = $request->input('plan_id');

        // Store selected plan in session
        session(['selected_plan' => $planId]);

        return redirect()->route('onboarding.complete');
    }
}
