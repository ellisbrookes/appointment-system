<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\StripeClient;

abstract class Controller
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
            $prices - $this->stripe->prices->all(['active' => true]);

            $productsWithPrices = collect($products->data)->map(function ($product) use ($price) {
                $productPrices = collect($prices->data)->filter(function ($price) use ($product) {
                    return $price->product === $product->id;
                });

                $product->prices = $productPrices;
                return $product;
            });

            return view('products.index', compact('productsWithPrices'));
        } catch (\Expection $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}