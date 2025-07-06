# Email Configuration Guide

This document explains how to configure email for both development and production environments.

## Development Environment (Mailtrap)

For development, we use Mailtrap to safely test emails without sending them to real recipients.

### 1. Setup Mailtrap Account

1. Go to [mailtrap.io](https://mailtrap.io) and create a free account
2. Create a new inbox in your Mailtrap dashboard
3. Copy the SMTP credentials

### 2. Configure `.env` for Development

Update your `.env` file with your Mailtrap credentials:

```env
# Mailtrap Configuration for Development
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username_here
MAIL_PASSWORD=your_mailtrap_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@appointmentsystem.test"
MAIL_FROM_NAME="${APP_NAME}"
```

### 3. Test Email in Development

Run the test email command:

```bash
php artisan email:test your-email@example.com
```

Check your Mailtrap inbox to see the email.

## Production Environment (SendGrid)

For production, we use SendGrid for reliable email delivery.

### 1. Setup SendGrid Account

1. Go to [sendgrid.com](https://sendgrid.com) and create an account
2. Verify your sender identity (domain or single sender)
3. Generate an API key in Settings > API Keys

### 2. Configure Production Environment

Update your production `.env` file:

```env
# SendGrid Configuration for Production
MAIL_MAILER=sendgrid
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"

# SendGrid API Key
SENDGRID_API_KEY=SG.your_sendgrid_api_key_here
```

### 3. Verify Production Setup

After deploying, test with:

```bash
php artisan email:test your-email@example.com
```

## Troubleshooting

### Common Issues

1. **"SendGrid API key is required"**: Make sure `SENDGRID_API_KEY` is set in production
2. **Mailtrap authentication failed**: Check your username/password in `.env`
3. **From address not verified**: In SendGrid, verify your sender domain or email

### Debug Commands

```bash
# Check current mail configuration
php artisan config:show mail

# Clear configuration cache
php artisan config:clear

# Test email sending
php artisan email:test

# Check logs for errors
tail -f storage/logs/laravel.log
```

### Environment-Specific Configuration

The system automatically uses:
- **Development**: Mailtrap (SMTP) when `MAIL_MAILER=smtp`
- **Production**: SendGrid when `MAIL_MAILER=sendgrid`

Never use SendGrid in development to avoid accidentally sending emails to real users.
