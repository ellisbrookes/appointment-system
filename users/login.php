<?php
session_start();

require_once "../setup.php";

$title = "Login";
include "../partials/shared/alerts.php";
include "../partials/shared/head.php";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Get user input
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate input for both fields with the same alert
  if (!$email && !$password) {
    Alert::SetAlert(
      AlertVariants::DANGER,
      "Please enter both email and password"
    );
  }

  // If email and password fields have been filled out
  if ($email && $password) {
    // Prepare and execute a query to fetch user data
    $stmt = $conn->prepare(
      "SELECT id, name, password FROM users WHERE email = ?"
    );
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch user data
    $user = $result->fetch_assoc();
    $storedHashedPassword = $user["password"];

    // Verify the password using password_hash() comparison
    if (password_verify($password, $storedHashedPassword)) {
      $_SESSION["user_id"] = $user["id"];
      $_SESSION["user_name"] = $user["name"];
      $_SESSION["user_email"] = $email;

      header("Location: ../index.php"); // Redirect to index

      exit();
    } else {
      Alert::SetAlert(AlertVariants::DANGER, "Invalid email or password");
    }
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
    <!-- left banner -->
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
