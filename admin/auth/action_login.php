<?php
include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/config.php");

if (isset($_POST['user_name']) && isset($_POST['password'])) {
    $username = $_POST['user_name'];
    $password = $_POST['password'];

    // Secure query to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, user_name, role_id, password FROM users WHERE user_name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_object();

    // Verify password
    if ($user && password_verify($password, $user->password)) {
        $_SESSION['login'] = true;
        $_SESSION['auth'] = $user->id;
        $_SESSION['user_id'] = $user->id;
        $_SESSION['role_id'] = $user->role_id;

        header('Location: ' . $burl . '/admin/index.php');
        exit();
    } else {
        $_SESSION['error_message'] = "Login Failed";
        header('Location: ' . $burl . '/admin/auth/login.php');
        exit();
    }
}
?>
