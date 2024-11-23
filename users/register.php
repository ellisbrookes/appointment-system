<?php
require '../vendor/autoload.php';
use SendGrid\Mail\Mail;
use SendGrid\Mail\Form;
require_once "../setup.php";
include "../partials/shared/alerts.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    UserSignup($conn);
}

function UserSignup($conn) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $tel = trim($_POST['tel']);
    $password = trim($_POST['password']);

    if (emailExists($email, $conn)) {
        Alert::setAlert(AlertVariants::WARNING, "This email is already registered.");
        header('Location: signup.php');
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, tel, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $tel, $hashed_password);

    if ($stmt->execute()) {
        sendWelcomeEmail($name, $email);

        Alert::setAlert(AlertVariants::SUCCESS, "Your account has been created successfully.");
        header('Location: login.php');
        exit();
    } else {
        echo "Error Saving User: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

function emailExists($email, $conn) {
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $stmt->store_result();
    $exists = $stmt->num_rows > 0;

    $stmt->close();
    return $exists;
}

function sendWelcomeEmail($name, $email) {
    $apiKey = $_ENV['SENDGRID_API_KEY'];
    $senderEmail = $_ENV['SENDGRID_SENDER_EMAIL'];
    $subject = "Welcome to Appointment System";

    $body = "
        <h1>Welcome, $name!</h1>
        <p>Thank you for signing up. We're excited to have you on board.</p>
        <p>Your registered email address is: <strong>$email</strong>.</p>
    ";

    $emailContent = new Mail();
    $emailContent->setFrom($senderEmail, 'Appointment System');
    $emailContent->setSubject($subject);
    $emailContent->addTo($email, $name);
    $emailContent->addContent("text/html", $body);

    $sendgrid = new \SendGrid($apiKey);

    try {
        $response = $sendgrid->send($emailContent);

        if ($response->statusCode() >= 200 && $response->statusCode() < 300) {
            return true;
        } else {
            error_log('Email sending failed. Status Code: ' . $response->statusCode());
            return false;
        }
    } catch (Exception $e) {
        error_log('Email error: ' . $e->getMessage());
        return false;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Appointments Calendar</title>
    <link rel="stylesheet" href="../assets/stylesheets/alerts.css">
    <link rel="stylesheet" href="../assets/stylesheets/auth.css">
</head>
<body>
<div class="auth-container">
    <form action="register.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="tel">Phone Number:</label>
        <input type="tel" id="tel" name="tel" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Register</button>
    </form>
</div>
</body>
</html>
