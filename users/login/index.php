<?php
require_once "../../setup.php";

$title = "Login";

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
  <title>Login</title>
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
        <h1 class="text-center">Login</h1>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email">

        <label for="password">Password:</label>
        <input type="password" id="password" name="password">

        <p>Forgot account?<a href="../../users/forgot"><b>Forgot password</b></a></p>

        <button type="submit" class="btn">Login</button>
      </form>
    </div>
  </div>
</body>
</html>
