<?php
header('Content-Type: application/json');
require_once("../config.php");

if ($_GET['type'] === 'cities') {
    $sql = "SELECT id, name FROM cities ORDER BY name ASC";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo json_encode(["error" => "Database error: " . mysqli_error($conn)]);
        exit;
    }

    $cities = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $cities[] = $row;
    }

    echo json_encode($cities);
    exit;
} else {
    echo json_encode(["error" => "Invalid request"]);
    exit;
}
?>
