<?php
require 'vendor/autoload.php';
use SendGrid\Mail\Mail;
require_once "./setup.php";
include "./partials/shared/alerts.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $service = $_POST['service'];
    $date = $_POST['date'];
    $userId = $_POST['user_id'];

    $userDetails = getUserDetails($userId, $conn);

    if (!$userDetails) {
        echo "Error: User not found.";
        exit();
    }

    $toName = $userDetails['name'];
    $toEmail = $userDetails['email'];

    $stmt = $conn->prepare("INSERT INTO appointments (id, service, date, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $id, $service, $date, $userId);

    if ($stmt->execute()) {
        sendAppointmentEmail($toEmail, $toName, [
            'date' => $date,
            'service' => $service,
        ]);

        Alert::setAlert(
            AlertVariants::SUCCESS,
            "Your appointment has been booked successfully"
        );
        
        header('Location: index.php');
        exit();
    } else {
        echo "Error Saving Appointment: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

function getUserDetails($userId, $conn) {
    $stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

function sendAppointmentEmail($toEmail, $toName, $appointmentDetails) {
    $apiKey = $_ENV['SENDGRID_API_KEY'];
    $email = new Mail();

    $email->setFrom('hello@ebrookes.dev', 'Appointment System');
    $email->setSubject('Appointment Confirmation');
    $email->addTo($toEmail, $toName);

    $emailContent = "
        <h1>Appointment Confirmed</h1>
        <p>Dear $toName,</p>
        <p>Your appointment has been confirmed. Here are the details:</p>
        <p><strong>Date:</strong> {$appointmentDetails['date']}</p>
        <p><strong>Service:</strong> {$appointmentDetails['service']}</p>
        <p>We look forward to seeing you!</p>
    ";
    $email->addContent("text/html", $emailContent);

    $sendgrid = new \SendGrid($apiKey);

    try {
        $response = $sendgrid->send($email);
        return $response->statusCode();
    } catch (Exception $e) {
        error_log('Email error: ' . $e->getMessage());
        return false;
    }
}
?>