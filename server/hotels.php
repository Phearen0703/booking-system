<?php
require_once("../config.php");

$query = "
    SELECT hotels.*, 
        hotel_rooms.id AS room_id,
        hotel_rooms.room_type,
        hotel_rooms.price,
        hotel_rooms.availability,
        locations.id AS location_id,
        cities.name AS city_name,
        districts.name AS district_name,
        provinces.name AS province_name
    FROM hotels
    LEFT JOIN (
        SELECT id, hotel_id, room_type, price, availability
        FROM hotel_rooms
        ORDER BY price ASC
        LIMIT 1
    ) hotel_rooms ON hotels.id = hotel_rooms.hotel_id
    LEFT JOIN locations ON hotels.location_id = locations.id
    LEFT JOIN cities ON locations.city_id = cities.id
    LEFT JOIN districts ON cities.district_id = districts.id
    LEFT JOIN provinces ON districts.province_id = provinces.id
";

$result = mysqli_query($conn, $query);
$hotels = [];

while ($row = mysqli_fetch_assoc($result)) {
    $hotels[] = [
        "id" => $row['id'],
        "name" => $row['name'],
        "location" => $row['city_name'],
        "price" => "$" . $row['price'] . "/night",
        "rating" => 5,
        "image" => "admin/public/img/hotel/" . $row['image'],
    ];
}

header('Content-Type: application/json');
echo json_encode($hotels);
exit;
?>
