<?php

namespace Tests\Unit;

use App\Services\PricingService;
use Tests\TestCase;
use Mockery;
use Stripe\StripeClient;
use Stripe\Collection;
use PHPUnit\Framework\Attributes\Test;

class PricingServiceTest extends TestCase
{
    protected $mockStripeClient;
    protected $pricingService;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock the StripeClient
        $this->mockStripeClient = Mockery::mock(StripeClient::class);
        
        // Create a partial mock of PricingService to inject our mock StripeClient
        $this->pricingService = Mockery::mock(PricingService::class)->makePartial();
        $this->pricingService->shouldAllowMockingProtectedMethods();
        
        // Set the protected stripe property
        $reflection = new \ReflectionClass($this->pricingService);
        $stripeProperty = $reflection->getProperty('stripe');
        $stripeProperty->setAccessible(true);
        $stripeProperty->setValue($this->pricingService, $this->mockStripeClient);
    }

    #[Test]
    public function test_get_products_with_prices_returns_formatted_collection(): void
    {
        // Arrange
        $mockProducts = new Collection();
        $mockProducts->data = [
            (object) [
                'id' => 'prod_test1',
                'name' => 'Test Product 1',
                'description' => 'Test Description 1'
            ],
            (object) [
                'id' => 'prod_test2', 
                'name' => 'Test Product 2',
                'description' => 'Test Description 2'
            ]
        ];

        $mockPrices = new Collection();
        $mockPrices->data = [
            (object) [
                'id' => 'price_test1',
                'product' => 'prod_test1',
                'currency' => 'usd',
                'unit_amount' => 999,
                'recurring' => (object) ['interval' => 'month']
            ],
            (object) [
                'id' => 'price_test2',
                'product' => 'prod_test1',
                'currency' => 'eur',
                'unit_amount' => 899,
                'recurring' => (object) ['interval' => 'month']
            ],
            (object) [
                'id' => 'price_test3',
                'product' => 'prod_test2',
                'currency' => 'usd',
                'unit_amount' => 1999,
                'recurring' => (object) ['interval' => 'year']
            ]
        ];

        // Mock the Stripe products service
        $mockProductsService = Mockery::mock();
        $mockProductsService->shouldReceive('all')
            ->with(['active' => true])
            ->once()
            ->andReturn($mockProducts);

        // Mock the Stripe prices service
        $mockPricesService = Mockery::mock();
        $mockPricesService->shouldReceive('all')
            ->with(['active' => true])
            ->once()
            ->andReturn($mockPrices);

        $this->mockStripeClient->products = $mockProductsService;
        $this->mockStripeClient->prices = $mockPricesService;

        // Act
        $result = $this->pricingService->getProductsWithPrices();

        // Assert
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
        $this->assertCount(2, $result);

        // Check first product
        $firstProduct = $result->first();
        $this->assertEquals('prod_test1', $firstProduct->id);
        $this->assertEquals('Test Product 1', $firstProduct->name);
        $this->assertCount(2, $firstProduct->prices); // Two prices for this product

        // Check prices format
        $firstPrice = $firstProduct->prices->first();
        $this->assertEquals('price_test1', $firstPrice['id']);
        $this->assertEquals('usd', $firstPrice['currency']);
        $this->assertEquals(999, $firstPrice['unit_amount']);
        $this->assertEquals('month', $firstPrice['interval']);
        $this->assertEquals(10, $firstPrice['trial_days']);

        // Check second product
        $secondProduct = $result->last();
        $this->assertEquals('prod_test2', $secondProduct->id);
        $this->assertCount(1, $secondProduct->prices); // One price for this product
    }

    #[Test]
    public function test_get_products_with_prices_handles_products_without_prices(): void
    {
        // Arrange
        $mockProducts = new Collection();
        $mockProducts->data = [
            (object) [
                'id' => 'prod_no_price',
                'name' => 'Test Product Without Price',
                'description' => 'This test product has no prices'
            ]
        ];

        $mockPrices = new Collection();
        $mockPrices->data = []; // No prices

        $mockProductsService = Mockery::mock();
        $mockProductsService->shouldReceive('all')
            ->with(['active' => true])
            ->once()
            ->andReturn($mockProducts);

        $mockPricesService = Mockery::mock();
        $mockPricesService->shouldReceive('all')
            ->with(['active' => true])
            ->once()
            ->andReturn($mockPrices);

        $this->mockStripeClient->products = $mockProductsService;
        $this->mockStripeClient->prices = $mockPricesService;

        // Act
        $result = $this->pricingService->getProductsWithPrices();

        // Assert
        $this->assertCount(1, $result);
        $product = $result->first();
        $this->assertEquals('prod_no_price', $product->id);
        $this->assertTrue($product->prices->isEmpty());
    }

    #[Test]
    public function test_get_products_with_prices_handles_one_time_prices(): void
    {
        // Arrange
        $mockProducts = new Collection();
        $mockProducts->data = [
            (object) [
                'id' => 'prod_one_time',
                'name' => 'Test One Time Product',
                'description' => 'Test product with one-time pricing'
            ]
        ];

        $mockPrices = new Collection();
        $mockPrices->data = [
            (object) [
                'id' => 'price_one_time',
                'product' => 'prod_one_time',
                'currency' => 'usd',
                'unit_amount' => 4999,
                'recurring' => null // One-time payment
            ]
        ];

        $mockProductsService = Mockery::mock();
        $mockProductsService->shouldReceive('all')
            ->with(['active' => true])
            ->once()
            ->andReturn($mockProducts);

        $mockPricesService = Mockery::mock();
        $mockPricesService->shouldReceive('all')
            ->with(['active' => true])
            ->once()
            ->andReturn($mockPrices);

        $this->mockStripeClient->products = $mockProductsService;
        $this->mockStripeClient->prices = $mockPricesService;

        // Act
        $result = $this->pricingService->getProductsWithPrices();

        // Assert
        $product = $result->first();
        $price = $product->prices->first();
        $this->assertNull($price['interval']); // Should be null for one-time payments
    }

    #[Test]
    public function test_get_products_with_prices_throws_runtime_exception_on_stripe_error(): void
    {
        // Arrange
        $mockProductsService = Mockery::mock();
        $mockProductsService->shouldReceive('all')
            ->with(['active' => true])
            ->once()
            ->andThrow(new \Exception('Stripe API Error'));

        $this->mockStripeClient->products = $mockProductsService;

        // Act & Assert
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Stripe error: Stripe API Error');

        $this->pricingService->getProductsWithPrices();
    }

    #[Test]
    public function test_get_products_with_prices_throws_runtime_exception_on_prices_error(): void
    {
        // Arrange
        $mockProducts = new Collection();
        $mockProducts->data = [];

        $mockProductsService = Mockery::mock();
        $mockProductsService->shouldReceive('all')
            ->with(['active' => true])
            ->once()
            ->andReturn($mockProducts);

        $mockPricesService = Mockery::mock();
        $mockPricesService->shouldReceive('all')
            ->with(['active' => true])
            ->once()
            ->andThrow(new \Exception('Stripe Prices API Error'));

        $this->mockStripeClient->products = $mockProductsService;
        $this->mockStripeClient->prices = $mockPricesService;

        // Act & Assert
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Stripe error: Stripe Prices API Error');

        $this->pricingService->getProductsWithPrices();
    }

    #[Test]
    public function test_get_products_with_prices_returns_empty_collection_when_no_products(): void
    {
        // Arrange
        $mockProducts = new Collection();
        $mockProducts->data = [];

        $mockPrices = new Collection();
        $mockPrices->data = [];

        $mockProductsService = Mockery::mock();
        $mockProductsService->shouldReceive('all')
            ->with(['active' => true])
            ->once()
            ->andReturn($mockProducts);

        $mockPricesService = Mockery::mock();
        $mockPricesService->shouldReceive('all')
            ->with(['active' => true])
            ->once()
            ->andReturn($mockPrices);

        $this->mockStripeClient->products = $mockProductsService;
        $this->mockStripeClient->prices = $mockPricesService;

        // Act
        $result = $this->pricingService->getProductsWithPrices();

        // Assert
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
        $this->assertTrue($result->isEmpty());
    }

    #[Test]
    public function test_pricing_service_constructor_initializes_stripe_client(): void
    {
        // Arrange & Act
        $service = new PricingService();

        // Assert
        $reflection = new \ReflectionClass($service);
        $stripeProperty = $reflection->getProperty('stripe');
        $stripeProperty->setAccessible(true);
        $stripeClient = $stripeProperty->getValue($service);

        $this->assertInstanceOf(StripeClient::class, $stripeClient);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
