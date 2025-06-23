<?php

namespace Tests\Feature\Middleware;

use App\Http\Middleware\CheckSubscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Route;
use Tests\TestCase;

class CheckSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    private CheckSubscription $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new CheckSubscription();
    }

    public function test_allows_excluded_routes_without_subscription(): void
    {
        $user = User::factory()->create();
        
        $request = Request::create('/dashboard', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });
        
        $route = new Route('GET', '/dashboard', []);
        $route->name('dashboard');
        $request->setRouteResolver(function () use ($route) {
            return $route;
        });

        $next = function ($request) {
            return new Response('OK');
        };

        $response = $this->middleware->handle($request, $next);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getContent());
    }

    public function test_allows_pricing_route_without_subscription(): void
    {
        $user = User::factory()->create();
        
        $request = Request::create('/pricing', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });
        
        $route = new Route('GET', '/pricing', []);
        $route->name('pricing');
        $request->setRouteResolver(function () use ($route) {
            return $route;
        });

        $next = function ($request) {
            return new Response('OK');
        };

        $response = $this->middleware->handle($request, $next);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getContent());
    }

    public function test_allows_subscribed_user_to_continue(): void
    {
        $user = User::factory()->create();
        
        // Mock the subscribed method
        $user = $this->getMockBuilder(User::class)
            ->onlyMethods(['subscribed'])
            ->setConstructorArgs([['id' => 1, 'name' => 'Test User', 'email' => 'test@example.com']])
            ->getMock();
        
        $user->method('subscribed')->with('basic')->willReturn(true);
        
        $request = Request::create('/protected-route', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });
        
        $route = new Route('GET', '/protected-route', []);
        $route->name('protected-route');
        $request->setRouteResolver(function () use ($route) {
            return $route;
        });

        $next = function ($request) {
            return new Response('Protected Content');
        };

        $response = $this->middleware->handle($request, $next);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Protected Content', $response->getContent());
    }

    public function test_redirects_unsubscribed_user_to_pricing(): void
    {
        $user = User::factory()->create();
        
        // Mock the subscribed method to return false
        $user = $this->getMockBuilder(User::class)
            ->onlyMethods(['subscribed'])
            ->setConstructorArgs([['id' => 1, 'name' => 'Test User', 'email' => 'test@example.com']])
            ->getMock();
        
        $user->method('subscribed')->with('basic')->willReturn(false);
        
        $request = Request::create('/protected-route', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });
        
        $route = new Route('GET', '/protected-route', []);
        $route->name('protected-route');
        $request->setRouteResolver(function () use ($route) {
            return $route;
        });

        $next = function ($request) {
            return new Response('Protected Content');
        };

        $response = $this->middleware->handle($request, $next);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('/pricing', $response->headers->get('Location'));
    }

    public function test_redirects_guest_user_to_pricing(): void
    {
        $request = Request::create('/protected-route', 'GET');
        $request->setUserResolver(function () {
            return null; // Guest user
        });
        
        $route = new Route('GET', '/protected-route', []);
        $route->name('protected-route');
        $request->setRouteResolver(function () use ($route) {
            return $route;
        });

        $next = function ($request) {
            return new Response('Protected Content');
        };

        $response = $this->middleware->handle($request, $next);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('/pricing', $response->headers->get('Location'));
    }

    public function test_handles_null_route_name(): void
    {
        $user = User::factory()->create();
        
        $request = Request::create('/some-route', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });
        
        $route = new Route('GET', '/some-route', []);
        // Don't set route name, so it will be null
        $request->setRouteResolver(function () use ($route) {
            return $route;
        });

        $next = function ($request) {
            return new Response('Content');
        };

        $response = $this->middleware->handle($request, $next);

        // Should redirect since route name is not in excluded routes
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function test_middleware_with_session_alert(): void
    {
        $user = User::factory()->create();
        
        // Mock the User class to override the subscribed method
        $mockUser = $this->getMockBuilder(User::class)
            ->onlyMethods(['subscribed'])
            ->getMock();
        
        // Set all the required attributes
        $mockUser->id = $user->id;
        $mockUser->name = $user->name;
        $mockUser->email = $user->email;
        $mockUser->email_verified_at = $user->email_verified_at;
        $mockUser->password = $user->password;
        $mockUser->remember_token = $user->remember_token;
        $mockUser->telephone_number = $user->telephone_number;
        $mockUser->created_at = $user->created_at;
        $mockUser->updated_at = $user->updated_at;
        
        $mockUser->method('subscribed')->with('basic')->willReturn(false);

        $response = $this->actingAs($mockUser)->get('/dashboard/appointments');

        $response->assertRedirect(route('pricing'));
        $response->assertSessionHas('alert.type', 'danger');
        $response->assertSessionHas('alert.message', 'Your subscription is inactive. Please renew to continue.');
    }
}
