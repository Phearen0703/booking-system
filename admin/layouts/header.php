<?php
// Include the configuration file
include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/config.php");


// Check if user is logged in
if (!isset($_SESSION['auth'])) {
    header("Location: " . $burl . "/admin/auth/login.php"); // Redirect to login page
    exit;
}

// Fetch user details
$user_id = $_SESSION['auth'];
$user = $conn->query("SELECT * FROM users WHERE id = $user_id");

if ($user->num_rows > 0) {
    $users = $user->fetch_object();
} else {
    // If user is not found in the database, destroy session and redirect
    session_destroy();
    header("Location: " . $burl . "/admin/auth/login.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="keywords"
        content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/YOUR_FA_KIT.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="img/icons/icon-48x48.png" />

    <link rel="canonical" href="https://demo-basic.adminkit.io/" />

    <title><?php echo isset($title) ? $title : "Dashboard" ?></title>
    <link rel="stylesheet" href="<?php echo $burl ?>/admin/public/css/app.css">
    <script src="<?php echo $burl ?>/admin/public/js/app.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <nav id="sidebar" class="sidebar js-sidebar">
            <div class="sidebar-content js-simplebar">
                <a class="sidebar-brand" href="index.html">
                    <span class="align-middle">Booking System</span>
                </a>
                <ul class="sidebar-nav">
                    <!-- <li class="sidebar-header">
                        Pages
                    </li> -->

                    <li class="sidebar-item <?php echo $page == 'dashboard' ? 'active' : '' ?>">
                        <a class="sidebar-link" href="<?php echo $burl . "/admin/index.php"?>">

                        <i class="fa-solid fa-sliders align-middle"></i> <span
                                class="align-middle">Dashboard</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?php echo $page == 'user' ? 'active' : '' ?>">
                        <a class="sidebar-link" href="<?php echo $burl . "/admin/users/index.php"?>">
                        <i class="fa-solid fa-user align-middle"></i> <span class="align-middle">Users</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?php echo ($page == 'management' || $page == 'rooms') ? 'active' : ''; ?>">
                        <a class="sidebar-link" href="#managementMenu" data-bs-toggle="collapse" aria-expanded="false">
                            <i class="fa-solid fa-list-check align-middle"></i> 
                            <span class="align-middle">Management</span>
                        </a>
                        <ul id="managementMenu" class="collapse list-unstyled <?php echo ($page == 'management' || $page == 'rooms') ? 'show' : ''; ?>">
                            <li>
                                <a class="sidebar-link <?php echo $page == 'management' ? 'active' : ''; ?>" href="<?php echo $burl . "/admin/managements/index.php"; ?>">
                                    <i class="fa-solid fa-hotel align-middle"></i> <span class="align-middle">Hotel</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidebar-link <?php echo $page == 'rooms' ? 'active' : ''; ?>" href="<?php echo $burl . "/admin/managements/rooms/index.php"; ?>">
                                    <i class="fa-solid fa-bed align-middle"></i> <span class="align-middle">Room</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidebar-link <?php echo $page == 'locations' ? 'active' : ''; ?>" href="<?php echo $burl . "/admin/managements/locations/index.php"; ?>">
                                    <i class="fa-solid fa-file align-middle"></i> <span class="align-middle">Location</span>
                                </a>
                            </li>
                        </ul>
                    </li>




                    <li class="sidebar-item">
                        <a class="sidebar-link" href="pages-sign-up.html">
                        <i class="fa-solid fa-file-invoice-dollar align-middle"></i> <span
                                class="align-middle">Payment</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="main">
            <nav class="navbar navbar-expand navbar-light navbar-bg">
                <a class="sidebar-toggle js-sidebar-toggle">
                    <i class="hamburger align-self-center"></i>
                </a>

                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav navbar-align">

                        <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#"
                                data-bs-toggle="dropdown">
                                <i class="align-middle" data-feather="settings"></i>
                            </a>

                            <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#"
                                data-bs-toggle="dropdown">
                                <img src="<?php echo $burl ?>/admin/public/img/<?php echo $users -> photo ?>"
                                    class="avatar img-fluid rounded me-1" alt="User photo" /> <span
                                    class="text-dark"><?php echo $users -> first_name.' '. $users -> last_name?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="<?php echo $burl .'/admin/users/profile.php' ?>"><i class="align-middle me-1"
                                        data-feather="user"></i> Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?php echo $burl.'/admin/auth/action_logout.php' ?>">Log out</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- main content -->
            <main class="content">
                <div class="container-fluid p-0"></div>

                <?php 
    if (!isset($_SESSION['login']) || $_SESSION['login'] === false) {
        header('Location: ' . $burl . '/admin/auth/login.php');
    }

    ob_end_flush();
    ?>            