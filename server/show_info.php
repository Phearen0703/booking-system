<?php

header('Content-Type: application/json');
include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/config.php");

$response = [
    'loggedIn' => false,
    'username' => 'Guest',
    'user_image' => 'http://' . $_SERVER['HTTP_HOST'] . '/booking-system/admin/public/img/default.png'
];

if (isset($_SESSION['login']) && $_SESSION['login'] === true && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT first_name, last_name, photo FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $response['username'] = $user['first_name'].' '.$user['last_name'];
        $response['loggedIn'] = true;
        
        if (!empty($user['photo'])) {
            $response['user_image'] = 'http://' . $_SERVER['HTTP_HOST'] . '/booking-system/admin/public/img/' . $user['photo'];
        }
    }
}

echo json_encode($response);
?>
