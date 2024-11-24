<?php 

require_once "../setup.php";
include "../partials/shared/alerts.php";


// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate input
    if (!empty($email) && !empty($password)) {
        // Prepare and execute a query to fetch user data
        $stmt = $conn->prepare("SELECT name, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // Check if the email exists in the database
        if ($stmt->num_rows > 0) {
            // Fetch user data
            $stmt->bind_result($email, $hashed_password);
            $stmt->fetch();

            // Verify the password using password_hash() comparison
            if (password_verify($password, $hashed_password)) {
                // Password is correct, set session and redirect to protected page
                $_SESSION['user_name'] = $name;
                header("Location: ../index.php");  // Redirect to a dashboard or home page
                exit;
            } else {
                // Incorrect password
                Alert::SetAlert(
                    AlertVariants::DANGER,
                    "Invalid Password"
                );
            }
        } else {
            // No user found with that email
            Alert::SetAlert(
                AlertVariants::DANGER,
                "No user found with that email"
            );
        }
        $stmt->close();
    } else {
        // Missing email or password
        Alert::SetAlert(
            AlertVariants::DANGER,
            "Please enter both email and password"
        );
    }
}

Alert::renderAlert();  // Display alerts if any
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/stylesheets/alerts.css">
    <link rel="stylesheet" href="../assets/stylesheets/auth.css">
</head>
<body>
    <h2>Login</h2>
    <form action="login.php" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email"><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>
