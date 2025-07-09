<?php

namespace Tests\Feature;

use App\Http\Controllers\PricingController;
use App\Services\PricingService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Mockery;
use App\Models\User;

class PricingControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $mockPricingService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockPricingService = Mockery::mock(PricingService::class);
        $this->app->instance(PricingService::class, $this->mockPricingService);
    }

    #[Test]
    public function test_pricing_page_returns_successful_response_with_products(): void
    {
        // Arrange
        $mockProducts = collect([
            (object) [
                'id' => 'prod_test1',
                'name' => 'Basic Plan',
                'description' => 'Basic subscription plan',
                'prices' => collect([
                    [
                        'id' => 'price_test1',
                        'currency' => 'usd',
                        'unit_amount' => 999,
                        'interval' => 'month'
                    ]
                ])
            ],
            (object) [
                'id' => 'prod_test2',  
                'name' => 'Premium Plan',
                'description' => 'Premium subscription plan',
                'prices' => collect([
                    [
                        'id' => 'price_test2',
                        'currency' => 'usd',
                        'unit_amount' => 1999,
                        'interval' => 'month'
                    ]
                ])
            ]
        ]);

        $this->mockPricingService
            ->shouldReceive('getProductsWithPrices')
            ->once()
            ->andReturn($mockProducts);

        // Act
        $response = $this->get('/pricing');

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('pricing.index');
        $response->assertViewHas('productsWithPrices', $mockProducts);
    }

    #[Test]
    public function test_pricing_page_returns_successful_response_with_empty_products(): void
    {
        // Arrange
        $emptyProducts = collect();

        $this->mockPricingService
            ->shouldReceive('getProductsWithPrices')
            ->once()
            ->andReturn($emptyProducts);

        // Act
        $response = $this->get('/pricing');

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('pricing.index');
        $response->assertViewHas('productsWithPrices', $emptyProducts);
    }

    #[Test]
    public function test_pricing_page_handles_runtime_exception_from_pricing_service(): void
    {
        // Arrange
        $exceptionMessage = 'Stripe error: Unable to connect to Stripe API';
        
        $this->mockPricingService
            ->shouldReceive('getProductsWithPrices')
            ->once()
            ->andThrow(new \RuntimeException($exceptionMessage));

        // Act
        $response = $this->get('/pricing');

        // Assert
        $response->assertRedirect();
        $response->assertSessionHasErrors(['error' => $exceptionMessage]);
    }

    #[Test]
    public function test_pricing_controller_injection_works_correctly(): void
    {
        // Arrange
        $mockProducts = collect([
            (object) [
                'id' => 'prod_test',
                'name' => 'Test Product',
                'prices' => collect()
            ]
        ]);

        $this->mockPricingService
            ->shouldReceive('getProductsWithPrices')
            ->once()
            ->andReturn($mockProducts);

        // Act & Assert - This test verifies that dependency injection is working
        $controller = $this->app->make(PricingController::class);
        $this->assertInstanceOf(PricingController::class, $controller);
        
        // Make the request to ensure the controller method can be called
        $response = $this->get('/pricing');
        $response->assertStatus(200);
    }

    #[Test]
    public function test_pricing_page_route_is_accessible(): void
    {
        // Arrange
        $this->mockPricingService
            ->shouldReceive('getProductsWithPrices')
            ->once()
            ->andReturn(collect());

        // Act
        $response = $this->get(route('pricing'));

        // Assert
        $response->assertStatus(200);
    }

    #[Test]
    public function test_pricing_page_handles_products_with_multiple_prices(): void
    {
        // Arrange
        $mockProducts = collect([
            (object) [
                'id' => 'prod_multi_price',
                'name' => 'Flexible Plan',
                'description' => 'Plan with multiple pricing options',
                'prices' => collect([
                    [
                        'id' => 'price_monthly',
                        'currency' => 'usd',
                        'unit_amount' => 999,
                        'interval' => 'month'
                    ],
                    [
                        'id' => 'price_yearly',
                        'currency' => 'usd',
                        'unit_amount' => 9999,
                        'interval' => 'year'
                    ]
                ])
            ]
        ]);

        $this->mockPricingService
            ->shouldReceive('getProductsWithPrices')
            ->once()
            ->andReturn($mockProducts);

        // Act
        $response = $this->get('/pricing');

        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('productsWithPrices');
        
        $viewData = $response->getOriginalContent()->getData();
        $this->assertCount(2, $viewData['productsWithPrices']->first()->prices);
    }

    #[Test]
    public function test_pricing_page_handles_products_with_no_prices(): void
    {
        // Arrange
        $mockProducts = collect([
            (object) [
                'id' => 'prod_no_price',
                'name' => 'Product Without Prices',
                'description' => 'A product that has no pricing configured',
                'prices' => collect()
            ]
        ]);

        $this->mockPricingService
            ->shouldReceive('getProductsWithPrices')
            ->once()
            ->andReturn($mockProducts);

        // Act
        $response = $this->get('/pricing');

        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('productsWithPrices');
        
        $viewData = $response->getOriginalContent()->getData();
        $this->assertTrue($viewData['productsWithPrices']->first()->prices->isEmpty());
    }

    #[Test]
    public function test_pricing_page_handles_different_currency_prices(): void
    {
        // Arrange
        $mockProducts = collect([
            (object) [
                'id' => 'prod_multi_currency',
                'name' => 'Global Plan',
                'description' => 'Plan available in multiple currencies',
                'prices' => collect([
                    [
                        'id' => 'price_usd',
                        'currency' => 'usd',
                        'unit_amount' => 999,
                        'interval' => 'month'
                    ],
                    [
                        'id' => 'price_eur',
                        'currency' => 'eur',
                        'unit_amount' => 899,
                        'interval' => 'month'
                    ]
                ])
            ]
        ]);

        $this->mockPricingService
            ->shouldReceive('getProductsWithPrices')
            ->once()
            ->andReturn($mockProducts);

        // Act
        $response = $this->get('/pricing');

        // Assert
        $response->assertStatus(200);
        $viewData = $response->getOriginalContent()->getData();
        $prices = $viewData['productsWithPrices']->first()->prices;
        
        $this->assertCount(2, $prices);
        $this->assertEquals('usd', $prices->first()['currency']);
        $this->assertEquals('eur', $prices->last()['currency']);
    }

    #[Test]
    public function test_authenticated_user_can_select_plan(): void
    {
        // Arrange
        $user = User::factory()->create();
        $planId = 'price_test123';
        
        // Act
        $response = $this->actingAs($user)
            ->post('/pricing/select-plan', ['plan_id' => $planId]);
        
        // Assert
        $response->assertRedirect(route('onboarding.complete'));
        $this->assertEquals($planId, session('selected_plan'));
    }

    #[Test]
    public function test_unauthenticated_user_cannot_select_plan(): void
    {
        // Arrange
        $planId = 'price_test123';
        
        // Act
        $response = $this->post('/pricing/select-plan', ['plan_id' => $planId]);
        
        // Assert
        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function test_select_plan_requires_plan_id(): void
    {
        // Arrange
        $user = User::factory()->create();
        
        // Act
        $response = $this->actingAs($user)
            ->post('/pricing/select-plan', []);
        
        // Assert
        $response->assertSessionHasErrors(['plan_id']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
