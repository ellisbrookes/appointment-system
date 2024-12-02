<?php
require "vendor/autoload.php";
use SendGrid\Mail\Mail;
require_once "./setup.php";
include "./partials/shared/alerts.php";

session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $service = $_POST["service"];
  $date = $_POST["date"];
  $userId = $_POST["user_id"];

  $userDetails = getUserDetails($userId, $conn);

  if (!$userDetails) {
    echo "Error: User not found.";
    exit();
  }

  $toName = $userDetails["name"];
  $toEmail = $userDetails["email"];

  $stmt = $conn->prepare(
    "INSERT INTO appointments (id, service, date, user_id) VALUES (?, ?, ?, ?)"
  );
  $stmt->bind_param("sssi", $id, $service, $date, $userId);

  if ($stmt->execute()) {
    sendAppointmentEmail($toEmail, $toName, [
      "date" => $date,
      "service" => $service,
    ]);

    Alert::setAlert(
      AlertVariants::SUCCESS,
      "Your appointment has been booked successfully"
    );

    header("Location: index.php");
    exit();
  } else {
    echo "Error Saving Appointment: " . $stmt->error;
  }

  $stmt->close();
  $conn->close();
}

function getUserDetails($userId, $conn)
{
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

function sendAppointmentEmail($toEmail, $toName)
{
  $apiKey = $_ENV["SENDGRID_API_KEY"];
  $sendgrid = new \SendGrid($apiKey);
  $emailTemplate = file_get_contents("../emails/create-appointment.html");

  $data = [
    "{{name}}" => $toName,
    "{{appointment_date}}" => $date,
    "{{appointment_service}}" => $service,
    "{{year}}" => date("Y"),
  ];

  $htmlContent = str_replace(
    array_keys($data),
    array_values($data),
    $emailTemplate
  );

  $email = new Mail();
  $email->setFrom("hello@ebrookes.dev", "Appointment System");
  $email->setSubject("Appointment Confirmation");
  $email->addTo($toEmail, $toName);
  $email->addContent("text/html", $htmlContent);

  try {
    $response = $sendgrid->send($email);
    return $response->statusCode();
  } catch (Exception $e) {
    error_log("Email error: " . $e->getMessage());
    return false;
  }
}
?>