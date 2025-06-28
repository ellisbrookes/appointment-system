<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Invitation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #e5e5e5;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-name {
            color: #2563eb;
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        .invitation-details {
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .role-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .role-admin {
            background-color: #fef3c7;
            color: #92400e;
        }
        .role-member {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .cta-button {
            display: inline-block;
            background-color: #2563eb;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 20px 0;
        }
        .cta-button:hover {
            background-color: #1d4ed8;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e5e5;
            font-size: 14px;
            color: #666;
        }
        .link-fallback {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
            word-break: break-all;
            font-family: monospace;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="company-name">{{ $company->name }}</h1>
            <p>You've been invited to join our team!</p>
        </div>

        <div class="invitation-details">
            <h3>Invitation Details</h3>
            <p><strong>Company:</strong> {{ $company->name }}</p>
            <p><strong>Role:</strong> 
                <span class="role-badge role-{{ $invitation->role }}">
                    {{ ucfirst($invitation->role) }}
                </span>
            </p>
            <p><strong>Invited Email:</strong> {{ $invitation->email }}</p>
        </div>

        <div style="text-align: center;">
            <p>Click the button below to accept your invitation:</p>
            <a href="{{ $invitationUrl }}" class="cta-button">
                @if($invitation->user_id)
                    Accept Invitation
                @else
                    Join & Create Account
                @endif
            </a>
        </div>

        <div class="link-fallback">
            <p><strong>If the button doesn't work, copy and paste this link:</strong></p>
            <p>{{ $invitationUrl }}</p>
        </div>

        @if(!$invitation->user_id)
        <div style="background-color: #fef3c7; padding: 15px; border-radius: 6px; margin: 20px 0;">
            <p><strong>Note:</strong> Since you don't have an account yet, clicking the link will take you to our registration page where you can create your account and automatically join {{ $company->name }}.</p>
        </div>
        @endif

        <div class="footer">
            <p>This invitation was sent to {{ $invitation->email }}. If you believe this was sent in error, please ignore this email.</p>
            <p>Need help? Contact the company administrator who sent this invitation.</p>
        </div>
    </div>
</body>
</html>
