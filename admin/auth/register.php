<?php
$title = "Register";
include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/config.php");


$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $re_password = mysqli_real_escape_string($conn, $_POST['re_password']);
    $role_id = 2; // Default role for regular users

    // Validate passwords
    if ($password !== $re_password) {
        $message = "<div class='alert alert-danger'>Passwords do not match!</div>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Handle image upload
        $photo_name = "";
        if (!empty($_FILES['photo']['name'])) {
            $photo_name = time() . "_" . $_FILES['photo']['name']; 
            $photo_tmp = $_FILES['photo']['tmp_name'];
            $photo_path = $_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/public/img/" . $photo_name;

            if (!move_uploaded_file($photo_tmp, $photo_path)) {
                $message = "<div class='alert alert-danger'>Failed to upload image.</div>";
            }
        }

        // Insert user into the database
        $query = "INSERT INTO users (first_name, last_name, contact, user_name, password, role_id, photo) 
                  VALUES ('$first_name', '$last_name', '$contact', '$user_name', '$hashed_password', '$role_id', '$photo_name')";

        if (mysqli_query($conn, $query)) {
            $user_id = mysqli_insert_id($conn);

            // Auto-login after registration
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $user_name;
            $_SESSION['role_id'] = $role_id;
            $_SESSION['photo'] = $photo_name;
            $_SESSION['login'] = true;

            header("Location: " . $burl . "/admin/index.php");
            exit();
        } else {
            $message = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body { background-color: #f8f9fa; }
        .card { border: none; border-radius: 15px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body>
    <section class="vh-100 d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <div class="card p-4">
                        <div class="row g-0 mt-3">
                            
                        <?php if (isset($message)) echo "$message"; ?>

                        <h5 class="text-center fs-1">BOOKING SYSTEM - Register</h5>
                            <div class="col-md-6 text-center d-flex align-items-center justify-content-center">
                            <img src="<?php echo $burl; ?>/admin/public/img/photos/img.png" class="img-fluid" alt="Illustration">
                            </div>
                            <div class="col-md-6 p-4">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label class="form-label">First Name</label>
                                        <input type="text" name="first_name" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" name="last_name" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Contact</label>
                                        <input type="text" name="contact" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">User Name</label>
                                        <input type="text" name="user_name" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Confirm Password</label>
                                        <input type="password" name="re_password" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Profile Photo</label>
                                        <input type="file" name="photo" class="form-control">
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success w-100">REGISTER</button>
                                    </div>
                                    <p class="text-center mt-3">Already have an account? <a href="<?php echo $burl . '/admin/auth/login.php' ?>" class="text-danger">Login</a></p>
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
