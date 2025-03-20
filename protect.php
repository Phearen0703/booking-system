<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);




// Check if user is logged in
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: /booking-system/admin/auth/login.php");
    exit();
}



// Protect admin folder (only allow PermissionID 1 or 2)
function protect_admin_folder() {
    if (!isset($_SESSION['role_id']) || ($_SESSION['role_id'] !== 1 && $_SESSION['role_id'] !== 2)) {
        header("Location: /booking-system");
        exit();
    }
}



// // Protect guest folder (only guests with PermissionID = 3)
// function protect_guest_folder() {
//     if (isset($_SESSION['role_id']) && $_SESSION['role_id'] !== "3") {
//         header("Location: /booking-system/admin/index.php");
//         exit();
//     }
// }

?>
