<?php
$title = "Login";
include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/config.php");

// Redirect if already logged in
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            background: linear-gradient(to right, #4e54c8, #8f94fb);
        }
        .login-container {
            max-width: 400px;
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
        .login-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            color: #4e54c8;
        }
        .form-control {
            border-radius: 10px;
            padding: 10px 15px;
        }
        .input-group-text {
            background: #f8f9fa;
            border: none;
        }
        .btn-primary {
            background-color: #4e54c8;
            border: none;
            padding: 10px;
            font-size: 16px;
            border-radius: 10px;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background-color: #3d42b7;
        }
        .forgot-password {
            font-size: 14px;
            text-align: right;
        }
        .register-text {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>

<body class="d-flex align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-6">
                <div class="login-container">
                    <h3 class="login-title">BOOKING SYSTEM</h3>

                    <?php if (isset($message)) echo "<div class='alert alert-info'>$message</div>"; ?>

                    <form action="<?php echo $burl . '/admin/auth/action_login.php' ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label">User Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" name="user_name" class="form-control" placeholder="Enter your username" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="rememberMe">
                                <label class="form-check-label" for="rememberMe">Remember me</label>
                            </div>
                            <a href="#" class="text-muted forgot-password">Forgot password?</a>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Login</button>

                        <p class="register-text mt-3">Don't have an account? <a href="register.php" class="text-primary">Register</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
