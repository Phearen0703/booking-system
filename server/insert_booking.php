<?php
header("Content-Type: application/json"); // Ensure JSON response

include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/config.php");

$response = ["success" => false]; // Default response

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ensure user is logged in
    if (!isset($_SESSION['auth'])) {
        $response["message"] = "Unauthorized. Please log in.";
        echo json_encode($response);
        exit;
    }

    // Get the booking data
    $user_id = $_SESSION['auth'];
    $hotel_id = $_POST['hotelId'] ?? null;
    $room_id = $_POST['roomType'] ?? null;
    $check_in = $_POST['checkInDate'] ?? null;
    $check_out = $_POST['checkOutDate'] ?? null;

    // Validate inputs
    if (!$hotel_id || !$room_id || !$check_in || !$check_out) {
        $response["message"] = "All fields are required.";
        echo json_encode($response);
        exit;
    }

    // Check if hotel exists
    $hotel_check_query = "SELECT id FROM hotels WHERE id = ?";
    $stmt = $conn->prepare($hotel_check_query);
    $stmt->bind_param("i", $hotel_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $response["message"] = "Invalid Hotel ID.";
        echo json_encode($response);
        exit;
    }

    // Insert booking safely using prepared statements
    $query = "INSERT INTO bookings (user_id, hotel_id, room_id, check_in, check_out) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiiss", $user_id, $hotel_id, $room_id, $check_in, $check_out);

    if ($stmt->execute()) {
        $response["success"] = true;
        $response["message"] = "Booking successful!";
    } else {
        $response["message"] = "Database error: " . $stmt->error;
    }
} else {
    $response["message"] = "Invalid request method.";
}

echo json_encode($response);
?>
