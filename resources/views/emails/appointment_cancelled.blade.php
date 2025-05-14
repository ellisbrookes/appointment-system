<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Cancelled</title>
</head>
<body style="background-color: #f3f4f6; color: #111827; font-family: Arial, sans-serif; padding: 20px; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb;">
        <div style="background-color: #991b1b; color: #ffffff; padding: 20px; text-align: center;">
            <h1 style="margin: 0; font-size: 28px; font-weight: bold;">Appointment Cancelled</h1>
            <p style="margin: 8px 0 0; font-size: 16px;">We’re sorry to see you go!</p>
        </div>

        <div style="padding: 24px;">
            <p style="margin: 0 0 8px; font-size: 16px;">Hello,</p>
            <p style="margin: 16px 0 16px; font-size: 16px;">Your appointment has been successfully cancelled. Here are the original details for your reference:</p>
        </div>

        <div style="padding: 0 24px 16px; margin-top: -10px;">
            <h2 style="margin: 0 0 16px; font-size: 20px; font-weight: bold; color: #1f2937;">Appointment Details</h2>
            <div style="background-color: #fef2f2; border: 1px solid #fca5a5; padding: 16px; border-radius: 8px;">
                <p style="margin: 0 0 8px; font-size: 16px;"><strong>Service:</strong> {{ $service }}</p>
                <p style="margin: 0 0 8px; font-size: 16px;"><strong>Date:</strong> {{ \Carbon\Carbon::parse($date)->format('jS F Y') }}</p>
                <p style="margin: 0; font-size: 16px;"><strong>Time:</strong> {{ \Carbon\Carbon::parse($timeslot)->format('g:i A') }}</p>
            </div>
        </div>

        <div style="padding: 0 24px 24px;">
            <p style="margin: 0 0 16px; font-size: 16px;">If this was a mistake or you want to reschedule, please get in touch with us.</p>
            <a href="#" style="display: inline-block; background-color: #991b1b; color: #ffffff; text-decoration: none; font-weight: bold; padding: 12px 24px; border-radius: 6px; font-size: 16px;">Contact Us</a>
        </div>

        <div style="background-color: #1f2937; color: #d1d5db; text-align: center; font-size: 14px; padding: 16px;">
            <p style="margin: 0;">&copy; {{ date('Y') }} Appointment System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>