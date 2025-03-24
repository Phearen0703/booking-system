<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/config.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize the room type input
    $room_type = isset($_POST["room_type"]) ? trim($_POST["room_type"]) : '';

    // Validate input
    if (empty($room_type)) {
        echo json_encode(["error" => "Room type is required"]);
        exit;
    }

    // Prepare the SQL query to insert room type
    $query = "INSERT INTO hotel_rooms (room_type) VALUES (?)";
    
    // Create prepared statement
    if ($stmt = mysqli_prepare($conn, $query)) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "s", $room_type);

        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(["success" => "Room added successfully"]);
        } else {
            echo json_encode(["error" => "Database error: " . mysqli_error($conn)]);
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(["error" => "Failed to prepare the SQL query"]);
    }
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>
