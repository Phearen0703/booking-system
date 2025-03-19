<?php

    // session_start();
    // ob_start();

    // $base_url = "http://localhost";
    // $project_path = "/booking-system";
    // $burl = $base_url . $project_path;

    // $servername = "localhost";;
    // $username = "root";
    // $password = "";
    // $dbname = "booking_system";

    // // $_SESSION['login'] = (isset($_SESSION['login']) && $_SESSION['login'] == true) == true ? true :false;
    // // $_SESSION['login'] = !isset($_SESSION['login']) ? false : !$_SESSION['login'];

    // $_SESSION['login'] = isset($_SESSION['login']) && $_SESSION['login'] == true ? true : false;



    // $conn = new mysqli($servername, $username, $password, $dbname);




?>

<?php
// Start the session and buffer output
session_start();
ob_start();

// Define base URL and project path for easy reference in the application
$base_url = "http://localhost";
$project_path = "/booking-system";
$burl = $base_url . $project_path;

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "booking_system";

// Ensure login session is properly set to false by default if not already set
if (!isset($_SESSION['login'])) {
    $_SESSION['login'] = false;
}

// Create a connection to the MySQL database using MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Debugging: Log connection success
error_log("Database connected successfully");

?>
