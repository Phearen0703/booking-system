<?php
include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/config.php");
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

if ($action == 'fetch') {
    $rooms = [];
    $query = "SELECT id, room_name FROM rooms"; // Assuming table name is rooms
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $rooms[] = $row;
    }
    echo json_encode($rooms);
    exit;
}

if ($action == 'delete') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    if ($id) {
        $query = "DELETE FROM rooms WHERE id = $id";
        if (mysqli_query($conn, $query)) {
            echo json_encode(["success" => "Room deleted successfully"]);
        } else {
            echo json_encode(["error" => "Error deleting room: " . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(["error" => "Invalid room ID"]);
    }
    exit;
}
?>
