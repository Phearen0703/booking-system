<?php
$title = "Login";
include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/config.php");



// Redirect to dashboard if already logged in
if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
    header('Location: ' . $burl . '/admin/index.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-social {
            background-color: #3b5998;
            color: white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-social:hover {
            opacity: 0.8;
        }
    </style>
</head>

<body>
    <section class="vh-100 d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <div class="card p-4">
                        <div class="row g-0">

                        <?php if (isset($message)) echo "<div class='alert alert-info mt-3'>$message</div>"; ?>

                        <h5 class="text-center fs-1">BOOKING SYSTEM</h5>
                            <div class="col-md-6 text-center d-flex align-items-center justify-content-center">
                            <img src="<?php echo $burl; ?>/admin/public/img/photos/img.png" class="img-fluid" alt="Illustration">
                            </div>
                            <div class="col-md-6 p-4">
                                <form action="<?php echo $burl . '/admin/auth/action_login.php'?>" method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">User Name</label>
                                        <input type="text" name="user_name" class="form-control" placeholder="Enter a valid username" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <input type="checkbox" class="form-check-input" id="rememberMe">
                                            <label class="form-check-label" for="rememberMe">Remember me</label>
                                        </div>
                                        <a href="#" class="text-muted">Forgot password?</a>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary w-100">LOGIN</button>
                                    </div>
                                    <p class="text-center mt-3">Don't have an account? <a href="register.php" class="text-danger">Register</a></p>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3 text-muted">
                        Copyright © 2025. All rights reserved.
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
