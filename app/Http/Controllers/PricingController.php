<?php

namespace App\Http\Controllers;

use App\Services\PricingService;

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
}
