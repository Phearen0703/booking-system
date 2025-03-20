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
    $role_id = 3; // Default role for regular users
    $photo_name = ""; // Default photo

    // Check if passwords match
    if ($password !== $re_password) {
        $message = "<div class='alert alert-danger'>Passwords do not match!</div>";
    } else {
        // Check if username already exists
        $check_query = "SELECT user_name FROM users WHERE user_name = ?";
        $stmt_check = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt_check, "s", $user_name);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            $message = "<div class='alert alert-danger'>Username already exists!</div>";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Secure password

            // Image Upload Handling
            if (!empty($_FILES['photo']['name'])) {
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif']; // Allowed file types
                $photo_extension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

                if (!in_array($photo_extension, $allowed_extensions)) {
                    $message = "<div class='alert alert-danger'>Invalid file type. Only JPG, PNG, and GIF are allowed.</div>";
                } elseif ($_FILES['photo']['size'] > 2 * 1024 * 1024) { // 2MB file size limit
                    $message = "<div class='alert alert-danger'>File size must be less than 2MB.</div>";
                } else {
                    $photo_name = time() . "_" . basename($_FILES['photo']['name']); // Ensure unique name
                    $photo_tmp = $_FILES['photo']['tmp_name'];
                    $photo_path = $_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/public/img/" . $photo_name;

                    if (!move_uploaded_file($photo_tmp, $photo_path)) {
                        $message = "<div class='alert alert-danger'>Failed to upload image.</div>";
                        $photo_name = ""; // Reset photo name if upload fails
                    }
                }
            }

            // Insert User into Database
            $query = "INSERT INTO users (first_name, last_name, contact, user_name, password, role_id, photo) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "sssssis", $first_name, $last_name, $contact, $user_name, $hashed_password, $role_id, $photo_name);

            if (mysqli_stmt_execute($stmt)) {
                // Get user ID of the newly registered user
                $user_id = mysqli_insert_id($conn);

                // Auto-login after registration
                $_SESSION['login'] = true;
                $_SESSION['auth'] = $user_id;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['role_id'] = $role_id;
            
                
                    // Debugging: Check if session variables are set
                if (!isset($_SESSION['user_id'])) {
                    die("Error: Session not set.");
                }

                // Redirect to dashboard after login
                header("Location: /booking-system/index.php");
                exit();
            } else {
                $message = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
            }
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .form-control {
            border-radius: 10px;
        }
        .img-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 10px;
            display: none;
        }
    </style>
</head>
<body>
    <section class="vh-100 d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card p-4">
                        <div class="text-center">
                            <h3 class="fw-bold">Create an Account</h3>
                            <p class="text-muted">Fill in the details to register</p>
                        </div>

                        <!-- Display Message if Exists -->
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-info text-center">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>

                        <form action="register.php" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="first_name" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contact</label>
                                <input type="text" name="contact" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">User Name</label>
                                <input type="text" name="user_name" class="form-control" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="re_password" class="form-control" required>
                                </div>
                            </div>
                            <div class="text-center">
                                <img id="preview" class="img-preview" alt="Profile Preview">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Profile Photo</label>
                                <input type="file" name="photo" class="form-control" accept="image/*" onchange="previewImage(event)">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">REGISTER</button>
                            <p class="text-center mt-3">Already have an account? <a href="<?php echo $burl . '/admin/auth/login.php' ?>" class="text-primary">Login</a></p>
                        </form>
                    </div>
                    <div class="text-center mt-3 text-muted">
                        &copy; 2025 Booking System. All rights reserved.
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        function previewImage(event) {
            const preview = document.getElementById('preview');
            const file = event.target.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

