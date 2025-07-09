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

        // Define excluded routes
        $excludedRoutes = ['pricing', 'dashboard'];

        // Skip the middleware check for excluded routes
        if (in_array($request->route()->getName(), $excludedRoutes)) {
            return $next($request);
        }

        // Check subscription status
        if ($user && $user->subscribed('default')) {
            return $next($request);
        }

        // Redirect to pricing page if the subscription is inactive
        return redirect()->route('pricing')->with('alert', [
            'type' => 'danger',
            'message' => 'Your subscription is inactive. Please renew to continue.',
        ]);
    }
}
