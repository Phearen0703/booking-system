<?php
require_once("../config.php");

$query = "
            SELECT 
                hotels.*, 
                hr.id AS room_id,
                hr.room_type,
                hr.price,
                hr.availability,
                locations.id AS location_id,
                cities.name AS city_name,
                districts.name AS district_name,
                provinces.name AS province_name
            FROM hotels
            LEFT JOIN hotel_rooms hr 
                ON hotels.id = hr.hotel_id 
                AND hr.price = (
                    SELECT MIN(price) FROM hotel_rooms WHERE hotel_id = hotels.id
                )
            LEFT JOIN locations 
                ON hotels.location_id = locations.id
            LEFT JOIN cities 
                ON locations.city_id = cities.id
            LEFT JOIN districts 
                ON cities.district_id = districts.id
            LEFT JOIN provinces 
                ON districts.province_id = provinces.id;
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
