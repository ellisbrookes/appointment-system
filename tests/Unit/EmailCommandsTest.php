<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Console\Commands\DiagnoseEmail;
use App\Console\Commands\TestEmail;
use PHPUnit\Framework\Attributes\Test;

class EmailCommandsTest extends TestCase
{
    #[Test]
    public function diagnose_email_command_has_correct_signature(): void
    {
        // Arrange & Act
        $command = new DiagnoseEmail();

        // Assert
        $this->assertEquals('email:diagnose', $command->getName());
        $this->assertStringContainsString('Diagnose email configuration', $command->getDescription());
    }

    #[Test]
    public function test_email_command_has_correct_signature(): void
    {
        // Arrange & Act
        $command = new TestEmail();

        // Assert
        $this->assertEquals('email:test', $command->getName());
        $this->assertStringContainsString('Send a test email', $command->getDescription());
    }

    #[Test]
    public function diagnose_email_command_can_be_instantiated(): void
    {
        // Arrange & Act
        $command = new DiagnoseEmail();

        // Assert
        $this->assertInstanceOf(DiagnoseEmail::class, $command);
    }

    #[Test]
    public function test_email_command_can_be_instantiated(): void
    {
        // Arrange & Act
        $command = new TestEmail();

        // Assert
        $this->assertInstanceOf(TestEmail::class, $command);
    }
}
