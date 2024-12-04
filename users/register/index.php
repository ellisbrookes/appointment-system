<?php
require_once "../../setup.php";

$title = "Register";

include "../../partials/shared/alerts.php";
include "../../partials/shared/head.php";

// Display alerts if any
Alert::renderAlert();
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
    <!-- left banner -->
    <aside class="auth-banner"></aside>

    <!-- right form -->
    <div class="auth-form">
      <form action="./submit.php" method="POST" class="auth-form-form">
        <h1 class="text-center">Register</h1>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="tel">Phone Number:</label>
        <input type="tel" id="tel" name="tel" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <p>Already have an account?<a href="../../users/login"><b>Login</b></a></p>

        <button type="submit" class="btn">Register</button>
      </form>
    </div>
  </div>
</body>
</html>
