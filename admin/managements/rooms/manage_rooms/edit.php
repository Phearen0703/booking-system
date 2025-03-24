<?php
include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/config.php");
header('Content-Type: application/json');

$room_name = isset($_POST['room_name']) ? trim($_POST['room_name']) : '';
$room_id = isset($_POST['room_id']) ? intval($_POST['room_id']) : 0;

if (empty($room_name)) {
    echo json_encode(["error" => "Room name is required"]);
    exit;
}

if ($room_id) {
    // Update existing room
    $query = "UPDATE rooms SET room_name = '$room_name' WHERE id = $room_id";
    if (mysqli_query($conn, $query)) {
        echo json_encode(["success" => "Room updated successfully"]);
    } else {
        echo json_encode(["error" => "Error updating room: " . mysqli_error($conn)]);
    }
} else {
    // Add new room
    $query = "INSERT INTO rooms (room_name) VALUES ('$room_name')";
    if (mysqli_query($conn, $query)) {
        echo json_encode(["success" => "Room added successfully"]);
    } else {
        echo json_encode(["error" => "Error adding room: " . mysqli_error($conn)]);
    }
}
?>
