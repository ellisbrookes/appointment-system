<?php
require_once "../setup.php";
use SendGrid\Mail\Mail;
include "../partials/shared/alerts.php";

// Check if the token is present
if (isset($_GET["token"])) {
  $token = $_GET["token"];

  // Verify the token in the database
  $stmt = $conn->prepare(
    "SELECT id, reset_token_expiry FROM users WHERE reset_token = ?"
  );
  $stmt->bind_param("s", $token);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    // Token found, check if it's expired
    $stmt->bind_result($id, $expiry);
    $stmt->fetch();

    if (strtotime($expiry) < time()) {
      Alert::SetAlert(
        AlertVariants::DANGER,
        "The reset link has expired. Please request a new one"
      );
    } else {
      // If form is submitted, update the password
      if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];

        if ($password !== $confirm_password) {
          Alert::SetAlert(
            AlertVariants::DANGER,
            "Passwords do not match. Please try again."
          );
        } else {
          $hashed_password = password_hash($password, PASSWORD_DEFAULT);

          // Update the password in the database and clear the token
          $stmt->close();
          $stmt = $conn->prepare(
            "UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?"
          );
          $stmt->bind_param("si", $hashed_password, $id);

          if ($stmt->execute()) {
            Alert::SetAlert(
              AlertVariants::SUCCESS,
              "Your password has been reset successfully."
            );
            header("Location: login.php");
            exit();
          } else {
            Alert::SetAlert(
              AlertVariants::DANGER,
              "There was an error updating your password. Please try again."
            );
          }
        }
      }
    }
  } else {
    Alert::SetAlert(AlertVariants::DANGER, "Invalid reset token.");
  }
  $stmt->close();
} else {
  Alert::SetAlert(AlertVariants::DANGER, "No token provided.");
}

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
      <form action="reset.php?token=<?php echo htmlspecialchars(
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
