<?php
require_once '../setup.php';
use SendGrid\Mail\Mail;
include "../partials/shared/alerts.php";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = trim($_POST['email']);

  // validate email
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      Alert::SetAlert(AlertVariants::DANGER, "Invalid email format");
  } else {
    // Check if email exists in the users table
    $stmt = $conn->prepare("SELECT id, name FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      // email exists, generate a reset token
      $stmt->bind_result($id, $name);
      $stmt->fetch();

      // Generate a secure reset token
      $token = bin2hex(random_bytes(50));
      $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

      // update the database with the reset token and expiry
      $stmt->close();
      $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE id = ? ");
      $stmt->bind_param("ssi", $token, $expiry, $id);
      $stmt->execute();

      $emailSent = SendForgotEmail($email, $name, $token, [
        "email" => $email,
        "name" => $name,
        "token" => $token,
      ]);

      if ($emailSent) {
        // If email is sent successfully, show the success alert
        Alert::setAlert(
          AlertVariants::SUCCESS,
          "Reset password email sent successfully."
        );
        header("Location: ../index.php");
        exit();
      } else {
        // If email failed, show a warning alert but still redirect to register page
        Alert::setAlert(
          AlertVariants::WARNING,
          "There was an error sending your reset password email, please try again."
        );
        exit();
      }

      Alert::renderAlert();
    }
  }
}

function SendForgotEmail($toEmail, $toName, $token) 
{
  $apiKey = $_ENV["SENDGRID_API_KEY"];
  $email = new Mail();

  $email->setFrom("hello@ebrookes.dev", "Appointment System");
  $email->setSubject("Forgot Password Instructions");
  $email->AddTo($toEmail, $toName);

  $emailContent = "
    <p>Hello,<br><br>You requested to reset your password. Click the link below to reset it:<br><br></p>
    <a href='http://localhost:8000/users/reset.php?token={$token}'>Reset Password</a><br><br>Note: The link will expire in 1 hour.<br><br>
    <p>If you did not request this password reset please ignore this email</p>
  ";

  $email->addContent("text/html", $emailContent);

  $sendgrid = new \SendGrid($apiKey);

  try {
    $response = $sendgrid->send($email);

    if ($response->statusCode() === 200) {
      return true;
    } else {
      Alert::SetAlert(
        AlertVariants::DANGER, "Failed to send email, account created!"
      );

      return false;
    }
  } catch (Exception $e) {
    Alert::SetAlert(
      AlertVariants::DANGER, "Failed to send email, " . $e->getMessage()
    );
    
    return false;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
            <form action="forgot.php" method="POST" class="auth-form-form">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="Enter your email address" 
                    required>
                <button type="submit" class="btn">Send Reset Link</button>
            </form>
        </div>
    </div>
</body>
</html>
