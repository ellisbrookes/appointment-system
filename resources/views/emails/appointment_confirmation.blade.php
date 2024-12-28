<!DOCTYPE html>
<html>
<head>
    <title>Appointment Confirmation</title>
</head>
<body>
    <h1>Appointment Confirmation</h1>
    <p><strong>Service:</strong> {{ $service }}</p>
    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>
    <p><strong>Time:</strong> {{ $timeslot }}</p>
</body>
</html>
