<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Sync subscription with Stripe
        // $user->subscription('basic')->sync();

        // Check subscription status
        // if ($user && ! $user->subscribed('basic')) {
        if ($user->subscribed('basic')) {
            return redirect()->route('dashboard');
        } else {
             return redirect()->route('pricing')->with('alert', [
                'type' => 'danger',
                'message' => 'Your subscription is inactive. Please renew to continue.',
            ]);
        }

        return $next($request);
    }
}
?>
