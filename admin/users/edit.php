<?php
$title = "Edit Page";
$page = "user";
include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/header.php");

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>Invalid User ID.</div>";
    exit;
}

$user_id = $_GET['id'];
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<div class='alert alert-danger'>User not found.</div>";
    exit;
}

$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $contact = $_POST['contact'];
    $user_name = $_POST['user_name'];
    $role_id = $_POST['role_id'];
    
    $update_photo = ""; // Initialize the variable

    if (!empty($_FILES['photo']['name'])) {
        $photo_name = time() . "_" . $_FILES['photo']['name'];  // Rename file to avoid duplicates
        $photo_tmp = $_FILES['photo']['tmp_name'];  // Temporary file location
        $photo_path = $_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/public/img/" . $photo_name;  // Destination path
    
        if (move_uploaded_file($photo_tmp, $photo_path)) {
            $update_photo = ", photo = '$photo_name'";  // Set SQL update for photo
        } else {
            $message = "Failed to upload image.";
        }
    }
    
    $query = "UPDATE users SET first_name='$first_name', last_name='$last_name', contact='$contact', user_name='$user_name', role_id='$role_id' $update_photo WHERE id=$user_id";
    
    if (mysqli_query($conn, $query)) {
        $message = "User updated successfully.";
    } else {
        $message = "Failed to update user.";
    }
}
?>

<a href="index.php" class="btn btn-danger"><i class="fa-solid fa-reply"></i> Back</a>

<div class="mt-3">
    <form method="POST" enctype="multipart/form-data">
        <div class="card">
            <div class="card-header fs-4"><i class="fa-solid fa-edit"></i> Edit User</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" value="<?= $user['first_name']; ?>" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" value="<?= $user['last_name']; ?>" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact</label>
                            <input type="text" name="contact" value="<?= $user['contact']; ?>" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Photo</label>
                            <input type="file" accept="image/*" name="photo" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">User Name</label>
                            <input type="text" name="user_name" value="<?= $user['user_name']; ?>" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password (Leave blank to keep current)</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role_id" class="form-select" required>
                                <option value="">Select Role</option>
                                <?php
                                $role_query = "SELECT id, role_name FROM roles";
                                $roles = mysqli_query($conn, $role_query);

                                while ($row = mysqli_fetch_assoc($roles)) {
                                    $selected = ($user['role_id'] == $row['id']) ? 'selected' : '';
                                    echo "<option value='{$row['id']}' $selected>{$row['role_name']}</option>";
                                }
                                ?>
                            </select>
                            <?php if (!empty($user['photo'])) : ?>
                                <img src="<?= $burl .'/admin/public/img/'. $user['photo']; ?>" alt="User Photo" class="img-thumbnail mt-2" width="80">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Update User</button>
                <?php if (isset($message)) echo "<div class='alert alert-info mt-3'>$message</div>"; ?>
            </div>
        </div>
    </form>
</div>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/footer.php"); ?>
