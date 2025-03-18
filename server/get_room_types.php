<?php
require_once("../config.php");

// Assuming you've already connected to the database using PDO
if (isset($_GET['hotel_id'])) {
    $hotelId = $_GET['hotel_id'];
    
    // Prepare the SQL query to fetch room types for the hotel
    $stmt = $pdo->prepare("SELECT room_type FROM hotel_rooms WHERE hotel_id = :hotel_id AND availability > 0");
    $stmt->execute(['hotel_id' => $hotelId]);

    // Fetch all room types
    $roomTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the results as JSON
    echo json_encode($roomTypes);
}
?>
