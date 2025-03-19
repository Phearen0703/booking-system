<?php

header('Content-Type: application/json');
include($_SERVER['DOCUMENT_ROOT']."/booking-system/config.php");

$response = ["loggedIn" => isset($_SESSION['login']) && $_SESSION['login'] === true];

echo json_encode($response); // Output the full response with username, image, and loggedIn status
?>
