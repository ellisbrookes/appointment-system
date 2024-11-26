<?php

require_once "../setup.php";
include "../partials/shared/alerts.php";

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
  session_start(); // Start the session
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Get user input
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate input for both fields with the same alert
  if (!empty($email) && !empty($password)) {
    Alert::SetAlert(
      AlertVariants::DANGER,
      "Please enter both email and password"
    );
  }

  // If email and password fields have been filled out
  if ($email && $password) {
    // Prepare and execute a query to fetch user data
    $stmt = $conn->prepare("SELECT name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Fetch user data
    $stmt->bind_result($email, $hashed_password);
    $stmt->fetch();
    $stmt->close();

    // Verify the password using password_hash() comparison
    if (password_verify($password, $hashed_password)) {
      $_SESSION["user_name"] = $name; // Correct password, session is set
      header("Location: ../index.php"); // Redirect to index
      exit();
    } else {
      Alert::SetAlert(AlertVariants::DANGER, "Invalid Password");
    }
  } else {
    Alert::SetAlert(AlertVariants::DANGER, "No user found with that email");
  }
}

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
    <!--  -->
    <aside class="auth-banner"></aside>

    <!-- right form -->
    <div class="auth-form">
      <form action="login.php" method="POST" class="auth-form-form">
        <h1 class="text-center">Login</h1>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email">

        <label for="password">Password:</label>
        <input type="password" id="password" name="password">

        <p>Forgot account?<a href="forgot.php"><b>Forgot password</b></a></p>

        <button type="submit" class="btn">Login</button>
      </form>
    </div>
  </div>
</body>
</html>
