<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\StripeClient;

class PricingController extends Controller
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(env('STRIPE_SECRET'));
    }

    public function index()
    {
        try {
            $products = $this->stripe->products->all(['active' => true]);
            $prices = $this->stripe->prices->all(['active' => true]);

            $productsWithPrices = collect($products->data)->map(function ($product) use ($prices) {
                $productPrices = collect($prices->data)->filter(function ($price) use ($product) {
                    return $price->product === $product->id;
                })->map(function ($price) {
                    return [
                        'id' => $price->id,
                        'currency' => $price->currency,
                        'unit_amount' => $price->unit_amount,
                        'interval' => $price['recurring']['interval'] ?? null,

                    ];
                });

                $product->prices = $productPrices;
                return $product;
            });

            return view('pricing.index', compact('productsWithPrices'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}