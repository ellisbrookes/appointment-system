<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Appointment;
use App\Services\PricingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Mockery;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function homepage_returns_successful_response()
    {
        // Clear cache to ensure fresh data
        Cache::flush();

        // Create some test data
        User::factory()->count(5)->create();
        Appointment::factory()->count(3)->create();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('index');
        $response->assertViewHas(['productsWithPrices', 'userCount', 'appointmentCount']);
    }

    #[Test]
    public function homepage_displays_correct_user_count()
    {
        Cache::flush();
        
        $userCount = 10;
        User::factory()->count($userCount)->create();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('userCount', $userCount);
    }

    #[Test]
    public function homepage_displays_correct_appointment_count()
    {
        Cache::flush();
        
        $appointmentCount = 7;
        Appointment::factory()->count($appointmentCount)->create();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('appointmentCount', $appointmentCount);
    }

    #[Test]
    public function homepage_handles_pricing_service_errors_gracefully()
    {
        Cache::flush();

        // Mock the PricingService to throw an exception
        $this->mock(PricingService::class, function ($mock) {
            $mock->shouldReceive('getProductsWithPrices')
                 ->andThrow(new \RuntimeException('Stripe API error'));
        });

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('productsWithPrices');
        
        // Should have empty collection when service fails
        $productsWithPrices = $response->viewData('productsWithPrices');
        $this->assertTrue($productsWithPrices->isEmpty());
    }

    #[Test]
    public function homepage_caches_user_count()
    {
        Cache::flush();
        
        // Create users
        User::factory()->count(5)->create();

        // First request should cache the data
        $this->get('/');
        
        // Verify cache was set
        $this->assertTrue(Cache::has('userCount'));
        $this->assertEquals(5, Cache::get('userCount'));
    }

    #[Test]
    public function homepage_caches_appointment_count()
    {
        Cache::flush();
        
        // Create appointments
        Appointment::factory()->count(3)->create();

        // First request should cache the data
        $this->get('/');
        
        // Verify cache was set
        $this->assertTrue(Cache::has('appointmentCount'));
        $this->assertEquals(3, Cache::get('appointmentCount'));
    }

    #[Test]
    public function homepage_caches_products_with_prices()
    {
        Cache::flush();

        // Mock successful pricing service response
        $mockProducts = collect([
            (object) ['name' => 'Basic Plan', 'prices' => collect([['unit_amount' => 999, 'currency' => 'usd', 'interval' => 'month']])],
            (object) ['name' => 'Premium Plan', 'prices' => collect([['unit_amount' => 1999, 'currency' => 'usd', 'interval' => 'month']])],
        ]);

        $this->mock(PricingService::class, function ($mock) use ($mockProducts) {
            $mock->shouldReceive('getProductsWithPrices')
                 ->once()
                 ->andReturn($mockProducts);
        });

        // First request should cache the data
        $response = $this->get('/');
        
        // Verify cache was set
        $this->assertTrue(Cache::has('productsWithPrices'));
        $this->assertEquals($mockProducts, Cache::get('productsWithPrices'));
        
        $response->assertViewHas('productsWithPrices', $mockProducts);
    }

    #[Test]
    public function homepage_uses_cached_data_on_subsequent_requests()
    {
        Cache::flush();

        // Set cache manually
        Cache::put('userCount', 100, 60);
        Cache::put('appointmentCount', 50, 60);
        Cache::put('productsWithPrices', collect([
            (object) ['name' => 'Cached Plan', 'prices' => collect([['unit_amount' => 100, 'currency' => 'usd', 'interval' => 'month']])]
        ]), 60);

        // Mock should not be called since we're using cached data
        $this->mock(PricingService::class, function ($mock) {
            $mock->shouldNotReceive('getProductsWithPrices');
        });

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('userCount', 100);
        $response->assertViewHas('appointmentCount', 50);
        $response->assertViewHas('productsWithPrices');
    }

    #[Test]
    public function homepage_with_zero_users_and_appointments()
    {
        Cache::flush();
        
        // Ensure database is empty
        User::query()->delete();
        Appointment::query()->delete();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('userCount', 0);
        $response->assertViewHas('appointmentCount', 0);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
