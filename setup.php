<?php
require_once __DIR__ . "/vendor/autoload.php";

// Load the .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database connection
$servername = $_ENV["DB_HOST"];
$username= $_ENV["DB_USERNAME"];
$password = $_ENV["DB_PASSWORD"];
$dbname = $_ENV["DB_NAME"];

// Create an connection to mysql (with specifying a database)
$conn = new mysqli($servername, $username, $password);

// check connection
if ($conn->connect_error) {
  trigger_error("Connection failed: " . $conn->connect_error);
}