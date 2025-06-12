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
        $this->stripe = new StripeClient(env('STRIPE_SECRET'));
    }

    public function getProductsWithPrices(): Collection
    {
        try {
            $products = $this->stripe->products->all(['active' => true]);
            $prices = $this->stripe->prices->all(['active' => true]);

            return collect($products->data)->map(function ($product) use ($prices) {
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

        } catch (Exception $e) {
            throw new \RuntimeException("Stripe error: " . $e->getMessage());
        }
    }
}
