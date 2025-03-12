<?php
$title = "Create Page";
$page = "user";
include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/header.php");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form inputs
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $re_password = mysqli_real_escape_string($conn, $_POST['re_password']);
    $role_id = mysqli_real_escape_string($conn, $_POST['role_id']);
    
    // Check if passwords match
    if ($password !== $re_password) {
        $message = "Passwords do not match!";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Handle photo upload
        $photo = '';
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            // Validate image upload
            $photo_tmp = $_FILES['photo']['tmp_name'];
            $photo_name = $_FILES['photo']['name'];
            $photo_ext = pathinfo($photo_name, PATHINFO_EXTENSION);
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (in_array(strtolower($photo_ext), $allowed_ext)) {
                $photo_new_name = time() . '_' . $photo_name;
                $photo_upload_path = $_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/public/img/" . $photo_new_name;
                
                // Move the uploaded file to the server directory
                if (move_uploaded_file($photo_tmp, $photo_upload_path)) {
                    $photo = $photo_new_name; // Store the file name for database
                } else {
                    $message = "Failed to upload photo.";
                }
            } else {
                $message = "Invalid file type for photo. Allowed types: jpg, jpeg, png, gif.";
            }
        } else {
            $message = "Please upload a photo.";
        }

        // If there were no errors, proceed with the database insertion
        if (empty($message)) {
            $query = "INSERT INTO users (first_name, last_name, contact, user_name, password, role_id, photo) 
                      VALUES ('$first_name', '$last_name', '$contact', '$user_name', '$hashed_password', '$role_id', '$photo')";

            if (mysqli_query($conn, $query)) {
                $message = "User created successfully!";
            } else {
                $message = "Error creating user: " . mysqli_error($conn);
            }
        }
    }
}

?>


<div class="container">
<a href="<?php echo $burl . '/admin/users/index.php' ?>" class="btn btn-outline-danger mb-3">
    <i class="fa-solid fa-reply"></i> Back
</a>
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white fs-5 d-flex align-items-center">
            <i class="fa-solid fa-user-plus me-2"></i> Create User
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">First Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Last Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                                <input type="text" name="contact" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Photo</label>
                            <input type="file" accept="image/*" name="photo" class="form-control" required>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">User Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-user-tag"></i></span>
                                <input type="text" name="user_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Re-Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="re_password" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <div class="d-flex">
                                <select name="role_id" class="form-select me-2" required>
                                    <option value="">Select Role</option>
                                    <?php
                                    $query = "SELECT id, role_name FROM roles";
                                    $roles = mysqli_query($conn, $query);

                                    while ($row = mysqli_fetch_assoc($roles)) {
                                        echo "<option value='{$row['id']}'>{$row['role_name']}</option>";
                                    }
                                    ?>
                                </select>
                                <a href="<?php echo $burl . '/admin/users/roles/index.php'; ?>" class="btn btn-success">
                                    <i class="fa-solid fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-user-plus"></i> Add User
                    </button>
                </div>

                <!-- Message Display -->
                <?php if (isset($message)) : ?>
                    <div class="alert alert-info mt-3 text-center"><?php echo $message; ?></div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>


<?php include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/footer.php"); ?>
