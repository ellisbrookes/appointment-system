@php
    use Carbon\Carbon;
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmed</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #10b981; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background-color: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
        .appointment-details { background-color: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .detail-row { display: flex; justify-content: space-between; margin: 10px 0; padding: 8px 0; border-bottom: 1px solid #eee; }
        .detail-label { font-weight: bold; color: #555; }
        .detail-value { color: #333; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
        .success-badge { background-color: #10b981; color: white; padding: 8px 16px; border-radius: 20px; display: inline-block; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Appointment Confirmed!</h1>
            <p>Your appointment has been approved</p>
        </div>
        
        <div class="content">
            <p>Hello <strong>{{ $appointment->customer_name }}</strong>,</p>
            
            <p>Great news! Your appointment with <strong>{{ $appointment->company->name }}</strong> has been confirmed.</p>
            
            <div class="success-badge">
                Status: Confirmed
            </div>
            
            <div class="appointment-details">
                <h3>📅 Appointment Details</h3>
                
                <div class="detail-row">
                    <span class="detail-label">Service:</span>
                    <span class="detail-value">{{ $appointment->service }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Date:</span>
                    <span class="detail-value">{{ Carbon::parse($appointment->date)->format('l, F j, Y') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Time:</span>
                    <span class="detail-value">{{ Carbon::parse($appointment->timeslot)->format('g:i A') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Company:</span>
                    <span class="detail-value">{{ $appointment->company->name }}</span>
                </div>
                
                @if($appointment->company->email)
                <div class="detail-row">
                    <span class="detail-label">Company Email:</span>
                    <span class="detail-value">{{ $appointment->company->email }}</span>
                </div>
                @endif
                
                @if($appointment->company->phone_number)
                <div class="detail-row">
                    <span class="detail-label">Company Phone:</span>
                    <span class="detail-value">{{ $appointment->company->phone_number }}</span>
                </div>
                @endif
                
                @if($appointment->company->address)
                <div class="detail-row">
                    <span class="detail-label">Address:</span>
                    <span class="detail-value">{{ $appointment->company->address }}</span>
                </div>
                @endif
            </div>
            
            @if($appointment->customer_message)
            <div class="appointment-details">
                <h4>📝 Your Message:</h4>
                <p>{{ $appointment->customer_message }}</p>
            </div>
            @endif
            
            <h3>📋 What's Next?</h3>
            <ul>
                <li>Please arrive 5-10 minutes early for your appointment</li>
                <li>Bring a valid ID and any relevant documents</li>
                <li>If you need to reschedule or cancel, please contact the company directly</li>
            </ul>
            
            <p>If you have any questions, please don't hesitate to contact <strong>{{ $appointment->company->name }}</strong> directly.</p>
            
            <div class="footer">
                <p>This is an automated confirmation email.</p>
                <p>Appointment ID: #{{ $appointment->id }}</p>
            </div>
        </div>
    </div>
</body>
</html>
