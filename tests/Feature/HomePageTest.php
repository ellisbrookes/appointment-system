<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\PricingService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Mockery;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_homepage_returns_successful_response(): void
    {
        if (empty(env('STRIPE_SECRET'))) {
            $this->markTestSkipped('Skipping test: STRIPE_SECRET not set.');
        }

        // Create some test users
        User::factory()->count(3)->create();

        // Mock the PricingService
        $mockPricingService = Mockery::mock(PricingService::class);
        $mockPricingService->shouldReceive('getProductsWithPrices')
            ->once()
            ->andReturn(new Collection());
        
        $this->app->instance(PricingService::class, $mockPricingService);

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
