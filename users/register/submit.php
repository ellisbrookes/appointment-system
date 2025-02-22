<?php
require_once "../setup.php";
include "../partials/shared/alerts.php";

use SendGrid\Mail\Mail;

// Only proceed with POST requests
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = $_POST["name"];
  $email = $_POST["email"];
  $tel = $_POST["tel"];
  $password = $_POST["password"];

  // Validate required fields
  if (!$name || !$email || !$tel || !$password) {
    Alert::setAlert(AlertVariants::WARNING, "All fields are required.");

    header("Location: register.php");

    exit();
  }

  try {
    // Attempt to register the user
    $userId = registerUser($conn, $name, $email, $tel, $password);

    if ($userId) {
      Alert::setAlert(
        AlertVariants::WARNING,
        "User registered successfully. User ID: $userId"
      );

      // Send the welcome email
      $emailSent = sendWelcomeEmail($email, $name);

      if ($emailSent) {
        Alert::setAlert(
          AlertVariants::SUCCESS,
          "Your account has been created successfully."
        );

        header("Location: ../appointments/index.php");

        exit();
      } else {
        Alert::setAlert(
          AlertVariants::SUCCESS,
          "User created, but email sending failed."
        );

        header("Location: register.php");

        exit();
      }
    } else {
      Alert::setAlert(AlertVariants::WARNING, "Error saving user.");

      header("Location: register.php");

      exit();
    }
  } catch (Exception $e) {
    error_log("Exception during registration: " . $e->getMessage());
    Alert::setAlert(
      AlertVariants::DANGER,
      "An unexpected error occurred. Please try again later."
    );
    header("Location: register.php");
    exit();
  }
}

// Function to send the welcome email
function sendWelcomeEmail($toEmail, $toName)
{
  $apiKey = $_ENV["SENDGRID_API_KEY"];
  $sendgrid = new \SendGrid($apiKey);
  $emailTemplate = file_get_contents("../emails/welcome.html");

  $data = [
    "{{name}}" => $toName,
    "{{email}}" => $toEmail,
    "{{year}}" => date("Y"),
  ];

  $htmlContent = str_replace(
    array_keys($data),
    array_values($data),
    $emailTemplate
  );

  $email = new Mail();
  $email->setFrom("hello@ebrookes.dev", "Appointment System");
  $email->setSubject("Welcome to Appointment System");
  $email->addTo($toEmail, $toName);
  $email->addContent("text/html", $htmlContent);

  try {
    $response = $sendgrid->send($email);
    return $response->statusCode() >= 200 && $response->statusCode() < 300;
  } catch (Exception $e) {
    error_log("Email Error: " . $e->getMessage());
    return false;
  }
}

// Function to register a new user
function registerUser($conn, $name, $email, $tel, $password)
{
  if (emailExists($email, $conn)) {
    Alert::setAlert(
      AlertVariants::WARNING,
      "This email is already registered."
    );

    return false; // Return false if email exists
  }

  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  $stmt = $conn->prepare(
    "INSERT INTO users (name, email, tel, password) VALUES (?, ?, ?, ?)"
  );

  if ($stmt === false) {
    error_log("SQL Prepare Error: " . $conn->error);
    return false;
  }

  $stmt->bind_param("ssss", $name, $email, $tel, $hashed_password);

  if ($stmt->execute()) {
    $userId = $conn->insert_id;
    $stmt->close();
    return $userId;
  } else {
    error_log("SQL Execution Error: " . $stmt->error);
  }

  $stmt->close();
  return false;
}

// Function to check if email already exists
function emailExists($email, $conn)
{
  $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
  if ($stmt === false) {
    error_log("SQL Prepare Error: " . $conn->error);
    return true; // Return true to block further processing if query fails
  }

  $stmt->bind_param("s", $email);
  $stmt->execute();

  $stmt->store_result();
  $exists = $stmt->num_rows > 0;

  $stmt->close();
  return $exists;
}
?>
