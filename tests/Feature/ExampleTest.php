<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        if (empty(config('services.stripe.secret'))) {
            $this->markTestSkipped('Skipping test: STRIPE_SECRET not set.');
        }

        $response = $this->get('/');

        $response->assertStatus(200);
    }

}
