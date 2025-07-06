<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Providers\SendGridServiceProvider;
use App\Mail\Transport\SendGridApiTransport;
use Illuminate\Foundation\Application;
use Illuminate\Mail\MailManager;
use PHPUnit\Framework\Attributes\Test;

class SendGridServiceProviderTest extends TestCase
{
    #[Test]
    public function it_can_be_instantiated(): void
    {
        // Arrange
        $app = new Application();
        
        // Act
        $provider = new SendGridServiceProvider($app);

        // Assert
        $this->assertInstanceOf(SendGridServiceProvider::class, $provider);
    }

    #[Test]
    public function it_has_correct_provides_array(): void
    {
        // Arrange
        $app = new Application();
        $provider = new SendGridServiceProvider($app);

        // Act
        $provides = $provider->provides();

        // Assert
        $this->assertIsArray($provides);
        $this->assertEmpty($provides); // This provider doesn't provide specific services
    }

    #[Test]
    public function sendgrid_transport_can_be_instantiated(): void
    {
        // Arrange & Act
        $transport = new SendGridApiTransport('test-api-key');

        // Assert
        $this->assertInstanceOf(SendGridApiTransport::class, $transport);
    }
}
