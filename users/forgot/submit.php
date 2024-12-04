<?php
require_once "../setup.php";
include "../partials/shared/alerts.php";

use SendGrid\Mail\Mail;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        Alert::setAlert(AlertVariants::DANGER, "Invalid email format");
        Alert::renderAlert();
        exit();
    } else {
        // Check if email exists in the users table
        $stmt = $conn->prepare("SELECT id, name FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Email exists, generate a reset token
            $stmt->bind_result($id, $name);
            $stmt->fetch();

            // Generate a secure reset token
            $token = bin2hex(random_bytes(50));
            $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

            // Update the database with the reset token and expiry
            $stmt->close();
            $stmt = $conn->prepare(
                "UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE id = ?"
            );
            $stmt->bind_param("ssi", $token, $expiry, $id);
            $stmt->execute();

            $emailSent = SendForgotEmail($email, $name, $token);

            if ($emailSent) {
                // If email is sent successfully, show the success alert
                Alert::setAlert(
                    AlertVariants::SUCCESS,
                    "Reset password email sent successfully."
                );
                header("Location: login.php");
                exit();
            } else {
                Alert::setAlert(
                    AlertVariants::WARNING,
                    "There was an error sending your reset password email, please try again."
                );
                header("Location: forgot.php");
                exit();
            }
        } else {
            Alert::setAlert(
                AlertVariants::DANGER,
                "Email not found in our records."
            );
            Alert::renderAlert();
            exit();
        }
    }
}

function SendForgotEmail($toEmail, $toName, $token)
{
    $apiKey = $_ENV["SENDGRID_API_KEY"];
    $sendgrid = new \SendGrid($apiKey);
    $emailTemplate = file_get_contents("../emails/forgot.html");

    $data = [
        "{{name}}" => $toName,
        "{{email}}" => $toEmail,
        "{{token}}" => $token,
        "{{year}}" => date("Y"),
    ];

    $htmlContent = str_replace(
        array_keys($data),
        array_values($data),
        $emailTemplate
    );

    $email = new Mail();
    $email->setFrom("hello@ebrookes.dev", "Appointment System");
    $email->setSubject("Forgot Password Instructions");
    $email->addTo($toEmail, $toName);
    $email->addContent("text/html", $htmlContent);

    try {
        $response = $sendgrid->send($email);

        if ($response->statusCode() >= 200 && $response->statusCode() < 300) {
            return true;
        } else {
            Alert::setAlert(
                AlertVariants::DANGER,
                "Failed to send email, account created!"
            );
            return false;
        }
    } catch (Exception $e) {
        Alert::setAlert(
            AlertVariants::DANGER,
            "Failed to send email, " . $e->getMessage()
        );
        return false;
    }
}
?>
