<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    /**
     * Test that the homepage returns a 200 status, skipping if Stripe secret is not set.
     */
    public function test_homepage_returns_successful_response(): void
    {
        if (empty(config('services.stripe.secret'))) {
            $this->markTestSkipped('Skipping test: STRIPE_SECRET not set.');
        }

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}

