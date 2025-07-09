<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\PricingService;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Stripe\StripeClient;

class SubscriptionController extends Controller
{
    protected $pricingService;
    protected $stripe;

    public function __construct(PricingService $pricingService)
    {
        $this->pricingService = $pricingService;
        $this->stripe = new StripeClient(config('cashier.secret'));
    }

    /**
     * Show subscription plans
     */
    public function index()
    {
        $user = Auth::user();
        $productsWithPrices = $this->pricingService->getProductsWithPrices();
        
        return view('subscription.index', compact('productsWithPrices', 'user'));
    }

    /**
     * Create a new subscription checkout session
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'price_id' => 'required|string',
        ]);

        $user = Auth::user();
        $priceId = $request->input('price_id');

        try {
            return $user
                ->newSubscription('default', $priceId)
                ->trialDays(10)
                ->allowPromotionCodes()
                ->checkout([
                    'success_url' => route('subscription.success'),
                    'cancel_url' => route('subscription.index'),
                ]);
        } catch (IncompletePayment $exception) {
            return redirect()->route('cashier.payment', [$exception->payment->id]);
        }
    }

    /**
     * Handle successful subscription
     */
    public function success()
    {
        return redirect()->route('dashboard')->with('alert', [
            'type' => 'success',
            'message' => 'Subscription activated successfully! Welcome aboard!'
        ]);
    }

    /**
     * Show billing portal
     */
    public function billing()
    {
        return Auth::user()->redirectToBillingPortal(route('dashboard'));
    }

    /**
     * Show Stripe Connect setup
     */
    public function stripeConnect()
    {
        $user = Auth::user();
        
        return view('subscription.stripe-connect', compact('user'));
    }

    /**
     * Create Stripe Connect account
     */
    public function createStripeConnect(Request $request)
    {
        $user = Auth::user();
        
        try {
            // Create Stripe Connect account
            $account = $this->stripe->accounts->create([
                'type' => 'express',
                'country' => 'US', // You might want to make this configurable
                'email' => $user->email,
                'business_type' => 'individual',
                'metadata' => [
                    'user_id' => $user->id
                ]
            ]);

            // Create account link for onboarding
            $accountLink = $this->stripe->accountLinks->create([
                'account' => $account->id,
                'refresh_url' => route('subscription.stripe-connect.refresh'),
                'return_url' => route('subscription.stripe-connect.return'),
                'type' => 'account_onboarding',
            ]);

            // Store the Stripe account ID with the user
            $user->update([
                'stripe_connect_id' => $account->id
            ]);

            return redirect($accountLink->url);
            
        } catch (\Exception $e) {
            return back()->with('alert', [
                'type' => 'danger',
                'message' => 'Failed to create Stripe Connect account: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Handle Stripe Connect return
     */
    public function stripeConnectReturn(Request $request)
    {
        $user = Auth::user();
        
        if ($user->stripe_connect_id) {
            try {
                $account = $this->stripe->accounts->retrieve($user->stripe_connect_id);
                
                if ($account->details_submitted) {
                    return redirect()->route('dashboard')->with('alert', [
                        'type' => 'success',
                        'message' => 'Stripe Connect account successfully set up! You can now receive payments.'
                    ]);
                }
            } catch (\Exception $e) {
                // Handle error
            }
        }

        return redirect()->route('subscription.stripe-connect')->with('alert', [
            'type' => 'warning',
            'message' => 'Stripe Connect setup incomplete. Please try again.'
        ]);
    }

    /**
     * Handle Stripe Connect refresh
     */
    public function stripeConnectRefresh(Request $request)
    {
        $user = Auth::user();
        
        if ($user->stripe_connect_id) {
            try {
                $accountLink = $this->stripe->accountLinks->create([
                    'account' => $user->stripe_connect_id,
                    'refresh_url' => route('subscription.stripe-connect.refresh'),
                    'return_url' => route('subscription.stripe-connect.return'),
                    'type' => 'account_onboarding',
                ]);

                return redirect($accountLink->url);
            } catch (\Exception $e) {
                // Handle error
            }
        }

        return redirect()->route('subscription.stripe-connect')->with('alert', [
            'type' => 'danger',
            'message' => 'Failed to refresh Stripe Connect setup.'
        ]);
    }

    /**
     * Disconnect Stripe Connect account
     */
    public function disconnectStripeConnect()
    {
        $user = Auth::user();
        
        if ($user->stripe_connect_id) {
            $user->update(['stripe_connect_id' => null]);
        }

        return back()->with('alert', [
            'type' => 'success',
            'message' => 'Stripe Connect account disconnected successfully.'
        ]);
    }
}
