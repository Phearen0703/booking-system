<?php
header('Content-Type: application/json'); // Ensure JSON response
include($_SERVER['DOCUMENT_ROOT']."/booking-system/config.php");

if (!isset($_GET['hotel_id'])) {
    echo json_encode(["error" => "Missing hotel_id"]);
    exit();
}

$hotel_id = intval($_GET['hotel_id']); // Sanitize input

$query = "SELECT id, room_type, price, availability FROM hotel_rooms WHERE hotel_id = $hotel_id";
$result = $conn->query($query);

if (!$result) {
    echo json_encode(["error" => "Database error: " . $conn->error]);
    exit();
}

$rooms = [];
while ($row = $result->fetch_assoc()) {
    $rooms[] = $row;
}

echo json_encode($rooms);
?>
