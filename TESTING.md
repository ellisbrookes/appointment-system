# Testing Environment Setup

## Setup Instructions

1. Copy the testing environment example file:
   ```bash
   cp .env.testing.example .env.testing
   ```

2. Generate a new application key for testing:
   ```bash
   php artisan key:generate --env=testing
   ```

3. Add your Stripe test secret key to `.env.testing`:
   ```
   STRIPE_SECRET=sk_test_YOUR_ACTUAL_STRIPE_TEST_KEY_HERE
   ```
   
   **Note:** Replace `YOUR_ACTUAL_STRIPE_TEST_KEY_HERE` with your real Stripe test key.

## Running Tests

```bash
# Run all tests
php artisan test

# Run tests with coverage
php artisan test --coverage

# Run specific test file
php artisan test tests/Feature/AppointmentManagementTest.php

# Run tests with specific environment
php artisan test --env=testing
```

## Important Notes

- **Never commit `.env.testing`** - It contains sensitive keys and secrets
- The `.env.testing` file is added to `.gitignore` to prevent accidental commits
- Use `.env.testing.example` as a template for new environments
- Always use test keys/secrets, never production credentials in testing

## Test Database

The testing environment is configured to use SQLite in-memory database by default:
- `DB_CONNECTION=sqlite`
- `DB_DATABASE=:memory:`

This ensures tests are fast and don't interfere with your development database.
