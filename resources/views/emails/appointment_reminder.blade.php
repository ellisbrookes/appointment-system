@php
    use Carbon\Carbon;
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Reminder</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #3b82f6; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background-color: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
        .appointment-details { background-color: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #3b82f6; }
        .detail-row { display: flex; justify-content: space-between; margin: 10px 0; padding: 8px 0; border-bottom: 1px solid #eee; }
        .detail-label { font-weight: bold; color: #555; }
        .detail-value { color: #333; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
        .reminder-badge { background-color: #f59e0b; color: white; padding: 8px 16px; border-radius: 20px; display: inline-block; margin: 10px 0; }
        .call-to-action { background-color: #3b82f6; color: white; padding: 15px; text-align: center; border-radius: 8px; margin: 20px 0; }
        .prep-checklist { background-color: #e5f3ff; padding: 20px; border-radius: 8px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⏰ Appointment Reminder</h1>
            <p>Don't forget about your upcoming appointment!</p>
        </div>
        
        <div class="content">
            <p>Hello <strong>{{ $appointment->user ? $appointment->user->name : $appointment->customer_name }}</strong>,</p>
            
            <p>This is a friendly reminder that you have an appointment scheduled for <strong>tomorrow</strong> with <strong>{{ $appointment->company->name }}</strong>.</p>
            
            <div class="reminder-badge">
                📅 Tomorrow's Appointment
            </div>
            
            <div class="appointment-details">
                <h3>📋 Appointment Details</h3>
                
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
                
                @if($appointment->company->phone_number)
                <div class="detail-row">
                    <span class="detail-label">Phone:</span>
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
            
            <div class="call-to-action">
                <h3>⭐ We're looking forward to seeing you!</h3>
                <p>Please arrive 5-10 minutes early for your appointment.</p>
            </div>
            
            <div class="prep-checklist">
                <h4>📝 Preparation Checklist:</h4>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Arrive 5-10 minutes early</li>
                    <li>Have your appointment reference ready: #{{ $appointment->id }}</li>
                    @if($appointment->customer_message)
                    <li>Your special request: "{{ $appointment->customer_message }}"</li>
                    @endif
                </ul>
            </div>
            
            <h3>🔄 Need to Reschedule or Cancel?</h3>
            <p>If you need to make any changes to your appointment, please contact <strong>{{ $appointment->company->name }}</strong> as soon as possible:</p>
            <ul>
                @if($appointment->company->phone_number)
                <li>📞 Phone: {{ $appointment->company->phone_number }}</li>
                @endif
                @if($appointment->company->email)
                <li>📧 Email: {{ $appointment->company->email }}</li>
                @endif
            </ul>
            
            <div class="footer">
                <p>This is an automated reminder email.</p>
                <p>Appointment ID: #{{ $appointment->id }}</p>
                <p>{{ $appointment->company->name }}</p>
            </div>
        </div>
    </div>
</body>
</html>
