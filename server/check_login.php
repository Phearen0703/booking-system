
<?php
session_start();
header('Content-Type: application/json');

$response = ["loggedIn" => isset($_SESSION['login']) && $_SESSION['login'] === true];

echo json_encode($response);
?>
