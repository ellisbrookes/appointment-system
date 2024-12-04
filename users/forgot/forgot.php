<?php
require_once "../setup.php";

$title = "Forgot Password";

include "../partials/shared/alerts.php";
include "../partials/shared/head.php";

Alert::renderAlert();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="../assets/css/alerts.css">
  <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>
  <div class="auth-container">
    <aside class="auth-banner"></aside>

    <!-- right form -->
    <div class="auth-form">
      <form action="forgot_handler.php" method="POST" class="auth-form-form">
        <h1 class="text-center">Reset your password</h1>

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
  </body>
</html>
