<?php

require_once "../setup.php";

include "../partials/shared/alerts.php";

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

?>