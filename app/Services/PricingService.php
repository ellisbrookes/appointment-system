<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Collection;
use Stripe\StripeClient;

class PricingService
{
    protected $stripe;

    public function __construct()
    {
        $stripeSecret = config('cashier.secret');
        
        if (empty($stripeSecret)) {
            throw new \RuntimeException('Stripe secret key is not configured. Please set STRIPE_SECRET in your .env file.');
        }
        
        $this->stripe = new StripeClient($stripeSecret);
    }

    public function getProductsWithPrices(): Collection
    {
        try {
            $products = $this->stripe->products->all(['active' => true]);
            $prices = $this->stripe->prices->all(['active' => true]);

            return collect($products->data)
                ->filter(function ($product) {
                    $name = strtolower($product->name ?? '');
                    $description = strtolower($product->description ?? '');
                    
                    $isTestProduct = str_contains($name, 'test') || str_contains($description, 'test');
                    
                    // In production, exclude test products
                    // In test/local environments, include test products
                    if (app()->environment('production')) {
                        return !$isTestProduct; // Exclude test products in production
                    } else {
                        return $isTestProduct; // Only show test products in test/local
                    }
                })
                ->map(function ($product) use ($prices) {
                $productPrices = collect($prices->data)->filter(function ($price) use ($product) {
                    return $price->product === $product->id;
                })->map(function ($price) {
                    return [
                        'id' => $price->id,
                        'currency' => $price->currency,
                        'unit_amount' => $price->unit_amount,
                        'interval' => $price->recurring->interval ?? null,
                    ];
                });

                $product->prices = $productPrices;

                return $product;
            });

        } catch (Exception $e) {
            throw new \RuntimeException("Stripe error: " . $e->getMessage());
        }
    }
}
