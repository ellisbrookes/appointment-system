<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Updated</title>
</head>
<body style="background-color: #f3f4f6; color: #111827; font-family: Arial, sans-serif; padding: 20px; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb;">
        <!-- Header -->
        <div style="background-color: #1f2937; color: #ffffff; padding: 20px; text-align: center;">
            <h1 style="margin: 0; font-size: 28px; font-weight: bold;">Appointment System</h1>
            <p style="margin: 8px 0 0; font-size: 16px;">Apppointment Updated</p>
        </div>

        <!-- Body -->
        <div style="padding: 24px;">
            <p style="margin: 0 0 8px; font-size: 16px;">Hello,</p>
            <p style="margin: 16px 0 16px; font-size: 16px;">We have updated your appointment. Please find the details below:</p>
        </div>

        <!-- Appointment Details -->
        <div style="padding: 0 24px 16px; margin-top: -10px;">
            <h2 style="margin: 0 0 16px; font-size: 20px; font-weight: bold; color: #1f2937;">Appointment Details</h2>
            <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; padding: 16px; border-radius: 8px;">
                <p style="margin: 0 0 8px; font-size: 16px;"><strong>Service:</strong> {{ $service }}</p>
                <p style="margin: 0 0 8px; font-size: 16px;"><strong>Date:</strong> {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>
                <p style="margin: 0; font-size: 16px;"><strong>Time:</strong> {{ $timeslot }}</p>
            </div>
        </div>

        <!-- Support -->
        <div style="padding: 0 24px 24px;">
            <p style="margin: 0 0 16px; font-size: 16px;">If you have any questions or need to make changes to your appointment, feel free to contact us.</p>
            <a href="#" style="display: inline-block; background-color: #2563eb; color: #ffffff; text-decoration: none; font-weight: bold; padding: 12px 24px; border-radius: 6px; font-size: 16px;">Contact Us</a>
        </div>

        <!-- Footer -->
        <div style="background-color: #1f2937; color: #d1d5db; text-align: center; font-size: 14px; padding: 16px;">
            <p style="margin: 0;">&copy; {{ date('Y') }} Appointment System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
