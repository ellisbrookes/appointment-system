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

// check if the database exists
$db_check_query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'";
$db_check_result = $conn->query($db_check_query);

if ($db_check_result->num_rows == 0) {
  // datbase does not exist, create it
  $create_db_query = "CREATE DATABASE $dbname";
  if ($conn->query($create_db_query) !== true) {
    trigger_error("Error creating database: " . $conn->error);
  }
}

// select excisting database to create tables if needed
$conn->select_db($dbname);

// Add users table if it doesn't exist
$create_users_table_query = "
  CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) DEFAULT '',
    email VARCHAR(50) NOT NULL UNIQUE,
    tel VARCHAR(15) UNIQUE,
    password VARCHAR(255) NOT NULL
  )";

if ($conn->query($create_users_table_query) !== true) {
  trigger_error("Error checking/creating 'users' table: " . $conn->error);
}

$create_appointments_table_query = "
  CREATE TABLE IF NOT EXISTS appointments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT UNSIGNED NOT NULL,
    CONSTRAINT appoint_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
  )";

if ($conn->query($create_appointments_table_query) !== true) {
  trigger_error("Error checking/creating 'appointments' table:" . $conn->error);
}

// Create a connection to the MySQL server
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>