<?php
require_once "../../setup.php";

$title = "Reset Password";

include "../../partials/shared/alerts.php";
include "../../partials/shared/head.php";

Alert::renderAlert();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
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
      <form action="./submit.php?token=<?php echo htmlspecialchars(
        $_GET["token"]
      ); ?>" method="POST" class="auth-form-form">
        <label for="password">New Password</label>
        <input type="password" id="password" name="password" required placeholder="Enter your new password">

        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm your new password">

        <button type="submit" class="btn">Reset Password</button>
      </form>
    </div>
  </div>
</body>
</html>
