<?php
use SendGrid\Mail\Mail;
require_once "../setup.php";
include "../partials/shared/alerts.php";

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Only proceed with POST requests
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = $_POST["name"];
  $email = $_POST["email"];
  $tel = $_POST["tel"];
  $password = $_POST["password"];

  // Attempt to register the user
  $userId = registerUser($conn, $name, $email, $tel, $password);

  if ($userId) {
    // User was successfully created, now attempt to send the welcome email
    $emailSent = sendWelcomeEmail($email, $name, [
      "email" => $email,
      "name" => $name,
    ]);

    if ($emailSent) {
      // If email is sent successfully, show the success alert
      Alert::setAlert(
        AlertVariants::SUCCESS,
        "Your account has been created successfully."
      );
      header("Location: ../index.php");
      exit();
    } else {
      // If email failed, show a warning alert but still redirect to register page
      Alert::setAlert(
        AlertVariants::WARNING,
        "User created, but email sending failed."
      );
      header("Location: register.php");
      exit();
    }
  } else {
    // User creation failed, show error alert
    Alert::setAlert(AlertVariants::WARNING, "Error saving user.");
    header("Location: register.php"); // Redirect back to register page on failure
    exit();
  }
}

// Function to handle user registration
function registerUser($conn, $name, $email, $tel, $password)
{
  // Check if email already exists
  if (emailExists($email, $conn)) {
    Alert::setAlert(
      AlertVariants::WARNING,
      "This email is already registered."
    );
    return false; // Return false if email exists
  }

  // Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Prepare SQL statement to insert new user into the database
  $stmt = $conn->prepare(
    "INSERT INTO users (name, email, tel, password) VALUES (?, ?, ?, ?)"
  );
  if ($stmt === false) {
    error_log("MySQL error: " . $conn->error);
    return false;
  }

  $stmt->bind_param("ssss", $name, $email, $tel, $hashed_password);

  if ($stmt->execute()) {
    return $conn->insert_id; // Return user ID after successful registration
  } else {
    // Log detailed SQL error
    error_log("SQL execute error: " . $stmt->error);
  }

  $stmt->close();
  return false;
}

// Function to check if the email already exists
function emailExists($email, $conn)
{
  // Check if email exists in the database
  $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
  if ($stmt === false) {
    error_log("SQL prepare error: " . $conn->error);
    return true; // Return true to avoid further processing if there is a query error
  }

  $stmt->bind_param("s", $email);
  $stmt->execute();

  // Store the result and check if any rows exist
  $stmt->store_result();
  $exists = $stmt->num_rows > 0;

  $stmt->close();
  return $exists;
}

// Function to send welcome email after registration
function sendWelcomeEmail($toEmail, $toName, $accountDetails)
{
  $apiKey = $_ENV["SENDGRID_API_KEY"];
  $email = new Mail();

  $email->setFrom("hello@ebrookes.dev", "Appointment System");
  $email->setSubject("Account Details");
  $email->addTo($toEmail, $toName);

  // Create the email content
  $emailContent = "
        <p>Welcome, {$toName}!</p>
        <p>Thank you for signing up. We're excited to have you on board.</p>
        <p>Your registered email address is: <strong>{$accountDetails["email"]}</strong>.</p>
    ";

  $email->addContent("text/html", $emailContent);

  $sendgrid = new \SendGrid($apiKey);

  try {
    $response = $sendgrid->send($email);
    if ($response->statusCode() === 200) {
      return true; // Email sent successfully
    } else {
      error_log(
        "Email sending failed. Status Code: " . $response->statusCode()
      );
      return false; // Email sending failed
    }
  } catch (Exception $e) {
    error_log("Email error: " . $e->getMessage());
    return false; // Email sending failed due to exception
  }
}

Alert::renderAlert();

// Display alerts if any
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/alerts.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>
    <div class="auth-container">
        <!-- Left banner -->
        <aside class="auth-banner"></aside>

        <!-- Right form -->
        <div class="auth-form">
            <form action="register.php" method="POST" class="auth-form-form">
                <h1 class="text-center">Register</h1>

                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="tel">Phone Number:</label>
                <input type="tel" id="tel" name="tel" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <p>Already have an account?<a href="login.php"><b>Login in</b></a></p>

                <button type="submit" class="btn">Register</button>
            </form>
        </div>
    </div>
</body>
</html>

