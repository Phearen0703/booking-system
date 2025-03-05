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

<a href="<?php echo $burl . '/admin/users/index.php' ?>" class="btn btn-danger"><i class="fa-solid fa-reply"></i>
    Back</a>
<div class="mt-3">
    <form method="POST" enctype="multipart/form-data">
        <div class="card">
            <div class="card-header fs-4"><i class="fa-solid fa-plus"></i> Create User</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
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
                            <label for="photo">Photo</label>
                            <input type="file" accept="image/*" id="photo" name="photo" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">User Name</label>
                            <input type="text" name="user_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Re-Password</label>
                            <input type="password" name="re_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <div class="form-group">
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
                                    <a href="<?php echo $burl . '/admin/users/roles/index.php'; ?>" class="btn btn-primary">
                                        <i class="fa-solid fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Add User</button>
                <?php if (isset($message)) echo "<div class='alert alert-info mt-3'>$message</div>"; ?>
            </div>
        </div>
    </form>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/footer.php"); ?>
